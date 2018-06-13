<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Services\Import;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Elastica\Exception\ElasticsearchException;
use Ramsey\Uuid\Uuid;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ProductBundle\Entity\Attribute;
use UmberFirm\Bundle\ProductBundle\Entity\Department;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductImport;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\ProductBundle\Entity\VariantImport;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\Store;
use UmberFirm\Bundle\SupplierBundle\Component\Storage\ImportStorageInterface;
use UmberFirm\Bundle\SupplierBundle\Entity\Import;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierAttributeMapping;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierManufacturerMapping;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierStoreMapping;
use UmberFirm\Bundle\SupplierBundle\Services\Format\FormatFactory;
use UmberFirm\Bundle\SupplierBundle\Services\Format\FormatInterface;

/**
 * Class ImportProduct
 *
 * @package UmberFirm\Bundle\SupplierBundle\Services\Import
 */
final class ImportProduct implements ImportProductInterface
{
    const BATCH_SIZE = 1000;

    /**
     * @var Supplier
     */
    private $supplier;

    /**
     * @var Shop
     */
    private $shop;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var FormatFactory
     */
    private $formatFactory;

    /**
     * @var FormatInterface
     */
    private $format;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ImportStorageInterface
     */
    private $importStorage;

    /**
     * @var array
     */
    private $cache;

    /**
     * @var Import
     */
    private $import;

    /**
     * ImportProduct constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param FormatFactory $formatFactory
     * @param ImportStorageInterface $importStorage
     * @param $locale
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        FormatFactory $formatFactory,
        ImportStorageInterface $importStorage,
        $locale
    ) {
        $this->locale = $locale;
        $this->formatFactory = $formatFactory;
        $this->entityManager = $entityManager;
        $this->importStorage = $importStorage;
        $this->entityManager->getConnection()->getConfiguration()->setSQLLogger(null);
    }

    /**
     * {@inheritdoc}
     */
    public function loadData(Import $import): void
    {
        $this->import = $import;
        $this->shop = $import->getShop();
        $this->supplier = $import->getSupplier();
        $data = $this->importStorage->getContent($import->getFilename());
        $this->format = $this->formatFactory->create($data, $import->getVersion());
    }

    /**
     * {@inheritdoc}
     */
    public function validate(): bool
    {
        return $this->format->isValid();
    }

    /**
     * @throws ElasticsearchException
     * @throws ORMException
     *
     * {@inheritdoc}
     */
    public function import(): void
    {
        $this->import->setStatus(Import::STATUS_IMPORTING);
        $this->import->setCountProducts($this->format->getCountProducts());
        $this->import->setCountVariants($this->format->getCountVariants());
        $this->import->setCountDepartments($this->format->getCountDepartments());

        $this->importProducts();
        $this->importVariants();
        $this->importDepartments();
        $this->resetQuantity();
        $this->updateProductVariantStock();
        $this->updateProductStock();
        $this->saveImportLogs();
    }

    /**
     * {@inheritdoc}
     */
    public function importProducts(): void
    {
        $i = 1;
        foreach ($this->format->buildProductStructure() as $item) {
            $productSupplierReference = $item['productSupplierReference'];
            if (null !== $this->findProductImport((string) $productSupplierReference)) {
                //TODO: add logs
                continue;
            }

            $product = $this->createProduct($item);
            $productImport = $this->createProductImport($productSupplierReference);
            $productImport->setProduct($product);

            $this->entityManager->persist($productImport);
            $this->entityManager->persist($product);

            if (($i % self::BATCH_SIZE) === 0) {
                $this->entityManager->flush();
                $this->clearEntities();
            }

            $i++;
        }
        $this->import->setImportedProducts((int) ($i - 1));

        $this->entityManager->flush();
        $this->clearEntities();
    }

    /**
     * {@inheritdoc}
     */
    public function importVariants(): void
    {
        $i = 1;
        foreach ($this->format->buildVariantStructure() as $item) {
            $variantSupplierReference = $item['variantSupplierReference'];
            $productSupplierReference = $item['productSupplierReference'];

            //TODO: refactor statements below without needs to unset current keys
            //remove all elements which aren't attributes
            unset($item['variantSupplierReference']);
            unset($item['productSupplierReference']);

            $productImport = $this->findProductImport((string) $productSupplierReference);
            if (null === $productImport) {
                //TODO: add logs
                continue;
            }

            if (null !== $this->findVariantImport((string) $variantSupplierReference)) {
                //TODO: add logs
                continue;
            }

            $product = $productImport->getProduct();
            $productVariant = $this->createProductVariant($item, $product);
            $variantImport = $this->createVariantImport($variantSupplierReference);
            $variantImport->setProductVariant($productVariant);

            $this->entityManager->persist($variantImport);
            $this->entityManager->persist($productVariant);

            if (($i % self::BATCH_SIZE) === 0) {
                $this->entityManager->flush();
                $this->clearEntities();
            }

            $i++;
        }
        $this->import->setImportedVariants((int) ($i - 1));

        $this->entityManager->flush();
        $this->clearEntities();
    }

    /**
     * {@inheritdoc}
     */
    public function importDepartments(): void
    {
        $i = 1;
        foreach ($this->format->buildDepartmentStructure() as $data) {
            $variantImport = $this->findVariantImport($data['variantSupplierReference']);
            if (null === $variantImport) {
                //TODO: add logs
                continue;
            }
            $productVariant = $variantImport->getProductVariant();

            $department = $this->findDepartment($data, $productVariant);
            if (null === $department) {
                $department = $this->createDepartment($data, $productVariant);
            }

            $department->setQuantity($data['quantity']);
            $department->setPrice($data['price']);
            $department->setSalePrice($data['salePrice']);
            $department->setPriority($this->supplier->getPosition());
            $this->entityManager->persist($department);

            $this->cache['departments'][] = $department->getId()->toString();

            if (($i % self::BATCH_SIZE) === 0) {
                $this->entityManager->flush();
                $this->clearEntities();
            }

            $i++;
        }
        $this->import->setImportedDepartments((int) ($i - 1));

        $this->entityManager->flush();
        $this->clearEntities();
    }

    /**
     * @param void
     */
    public function updateProductVariantStock(): void
    {
        $variantImportRepository = $this->entityManager->getRepository(VariantImport::class);
        $productVariantRepository = $this->entityManager->getRepository(ProductVariant::class);
        $iterable = $variantImportRepository->variantIteratorBySupplier($this->supplier, $this->shop);
        $i = 1;
        $updated = 0;

        /**
         * @var array|VariantImport[] $row
         */
        foreach ($iterable as $row) {
            /** @var ProductVariant $productVariant */
            $productVariant = $row['0']->getProductVariant();
            $updated += $productVariantRepository->updateStock($productVariant);

            if (($i % self::BATCH_SIZE) === 0) {
                $this->clearEntities();
            }
            $i++;
        }
        $this->import->setUpdatedVariants($updated);
        $this->clearEntities();
    }

    /**
     * {@inheritdoc}
     */
    public function updateProductStock(): void
    {
        $productImportRepository = $this->entityManager->getRepository(ProductImport::class);
        $productRepository = $this->entityManager->getRepository(Product::class);
        $iterable = $productImportRepository->productIteratorBySupplier($this->supplier, $this->shop);
        $i = 1;
        $updated = 0;

        /**
         * @var array|ProductImport[] $row
         */
        foreach ($iterable as $row) {
            /** @var Product $product */
            $product = $row['0']->getProduct();
            $updated += $productRepository->updateStock($product);

            if (($i % self::BATCH_SIZE) === 0) {
                $this->clearEntities();
            }
            $i++;
        }
        $this->import->setUpdatedProducts($updated);
        $this->clearEntities();
    }

    /**
     * @return void
     */
    public function saveImportLogs(): void
    {
        unset($this->cache);
        $this->import->setStatus(Import::STATUS_SUCCESS);
        $this->entityManager->persist($this->import);
        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    /**
     * @return void
     */
    private function resetQuantity(): void
    {
        $departmentRepository = $this->entityManager->getRepository(Department::class);
        $count = $departmentRepository->resetQuantityNotIn($this->cache['departments'], $this->supplier, $this->shop);
        $this->import->setDepartmentsOutOfStock($count);
    }

    /**
     * @param array $data
     * @param ProductVariant $productVariant
     *
     * @return null|Department
     */
    private function findDepartment(array $data, ProductVariant $productVariant): ?Department
    {
        $store = $this->getStore($data['store']);

        return $this->entityManager->getRepository(Department::class)->findOneBy(
            [
                'shop' => $this->shop,
                'supplier' => $this->supplier,
                'productVariant' => $productVariant,
                'store' => $store,
            ]
        );
    }

    /**
     * @param string $supplierReference
     *
     * @return null|ProductImport
     */
    private function findProductImport(string $supplierReference): ?ProductImport
    {
        return $this->entityManager->getRepository(ProductImport::class)->findOneBy(
            [
                'supplier' => $this->supplier,
                'shop' => $this->shop,
                'supplierReference' => $supplierReference,
            ]
        );
    }

    /**
     * @param string $supplierReference
     *
     * @return null|VariantImport
     */
    private function findVariantImport(string $supplierReference): ?VariantImport
    {
        return $this->entityManager->getRepository(VariantImport::class)->findOneBy(
            [
                'supplier' => $this->supplier,
                'shop' => $this->shop,
                'supplierReference' => $supplierReference,
            ]
        );
    }

    /**
     * @param string $supplierReference
     *
     * @return ProductImport
     */
    private function createProductImport(string $supplierReference): ProductImport
    {
        $productImport = new ProductImport();
        $productImport->setSupplier($this->supplier);
        $productImport->setShop($this->shop);
        $productImport->setSupplierReference($supplierReference);

        return $productImport;
    }

    /**
     * @param array $data
     *
     * @return Product
     */
    private function createProduct(array $data): Product
    {
        $product = new Product();
        $product->setIsElastica(false);
        $product->setShop($this->shop);
        $product->setArticle($data['article']);
        $product->setManufacturer($this->getManufacturer($data['manufacturer']));

        return $product;
    }

    /**
     * @param string $supplierReference
     *
     * @return VariantImport
     */
    private function createVariantImport(string $supplierReference): VariantImport
    {
        $variantImport = new VariantImport();
        $variantImport->setSupplier($this->supplier);
        $variantImport->setShop($this->shop);
        $variantImport->setSupplierReference($supplierReference);

        return $variantImport;
    }

    /**
     * @param array $data
     * @param Product $product
     *
     * @return ProductVariant
     */
    private function createProductVariant(array $data, Product $product): ProductVariant
    {
        $productVariant = new ProductVariant();
        $productVariant->setShop($this->shop);
        $productVariant->setProduct($product);

        foreach ($data as $attributeKey => $attributeValue) {
            $attribute = $this->getAttribute($attributeValue, $attributeKey);
            if (null === $attribute) {
                continue;
            }
            $productVariant->addProductVariantAttribute($attribute);
        }

        return $productVariant;
    }

    /**
     * @param array $data
     * @param ProductVariant $productVariant
     *
     * @return Department
     */
    private function createDepartment(array $data, ProductVariant $productVariant): Department
    {
        $department = new Department();
        $department->setProductVariant($productVariant);
        $department->setArticle($data['article']);
        $department->setUpc($data['upc']);
        $department->setEan13($data['ean13']);
        $department->setStore($this->getStore($data['store']));
        $department->setShop($this->shop);
        $department->setSupplier($this->supplier);

        return $department;
    }

    /**
     * @param string $value
     *
     * @return null|object|Manufacturer
     */
    private function getManufacturer(string $value): ?Manufacturer
    {
        $manufacturer = null;
        $mappingManufacturer = null;

        if (true === Uuid::isValid($value)) {
            $manufacturer = $this->entityManager->getRepository(Manufacturer::class)->find($value);
        }

        if (null === $manufacturer) {
            $mappingManufacturer = $this->entityManager
                ->getRepository(SupplierManufacturerMapping::class)
                ->findOneBySupplierManufacturer($this->supplier, $value);
        }

        if (true === $mappingManufacturer instanceof SupplierManufacturerMapping) {
            return $mappingManufacturer->getManufacturer();
        }

        return $manufacturer;
    }

    /**
     * @param string $value
     * @param string $key
     *
     * @return null|object|Attribute
     */
    private function getAttribute(string $value, string $key): ?Attribute
    {
        $attribute = null;
        $mappingAttribute = null;

        if (true === Uuid::isValid($value)) {
            $attribute = $this->entityManager->getRepository(Attribute::class)->find($value);
        }

        if (null === $attribute) {
            $mappingAttribute = $this->entityManager
                ->getRepository(SupplierAttributeMapping::class)
                ->findOneBySupplierAttribute($this->supplier, $key, $value);
        }

        if (true === $mappingAttribute instanceof SupplierAttributeMapping) {
            return $mappingAttribute->getAttribute();
        }

        return $attribute;
    }

    /**
     * @param string $value
     *
     * @return null|object|Store
     */
    private function getStore(string $value): ?Store
    {
        $store = null;
        $storeMapping = null;

        if (true === Uuid::isValid($value)) {
            $store = $this->entityManager->getRepository(Store::class)->find($value);
        }

        if (null === $store) {
            $storeMapping = $this->entityManager
                ->getRepository(SupplierStoreMapping::class)
                ->findOneBySupplierStore($this->supplier, $value);
        }

        if (true === $storeMapping instanceof SupplierStoreMapping) {
            return $storeMapping->getStore();
        }

        return $store;
    }

    /**
     * Clear all cached entities except Import, Shop and Supplier
     */
    private function clearEntities(): void
    {
        $identityMap = $this->entityManager->getUnitOfWork()->getIdentityMap();
        foreach ($identityMap as $class => $entity) {
            if (
                true === (Import::class === $class) ||
                true === (Shop::class === $class) ||
                true === (Supplier::class === $class)
            ) {
                continue;
            }
            $this->entityManager->clear($class);
        }
    }
}
