<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
use UmberFirm\Component\Elastica\Event\SubscriberTrait;

/**
 * Class UmberFirmImportProductsCommand
 *
 * @package UmberFirm\Bundle\CommonBundle\Command
 */
class UmberFirmImportProductsCommand extends ContainerAwareCommand
{
    use SubscriberTrait;

    const BATCH_SIZE = 500;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var FormatFactory
     */
    private $formatFactory;

    /**
     * @var Supplier
     */
    private $supplier;

    /**
     * @var Shop
     */
    private $shop;

    /**
     * @var ImportStorageInterface
     */
    private $importStorage;

    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var FormatInterface
     */
    private $format;

    /**
     * @var array
     */
    private $cache;

    /**
     * @var Import
     */
    private $import;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('umberfirm:import:products')
            ->setDescription('Command for importing supplier products.')
            ->addArgument('fileName', InputArgument::REQUIRED, 'File name')
            ->addArgument('supplier', InputArgument::REQUIRED, 'Supplier id (uuid)')
            ->addArgument('shop', InputArgument::REQUIRED, 'Supplier id (uuid)')
            ->addArgument('i-version', InputArgument::OPTIONAL, 'Version of import file', 'v1')
            ->addArgument('dir', InputArgument::OPTIONAL, 'Directory where file is placed', '/var/www/ufsoa-app/data')
            ->addOption('debug', null, InputOption::VALUE_NONE, 'Turn on debug mode')
            ->addOption('no-validation', null, InputOption::VALUE_NONE, 'Disable validation');
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->entityManager = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $this->formatFactory = $this->getContainer()->get('umber_firm_supplier.parser.format.factory');
        $this->importStorage = $this->getContainer()->get('umber_firm_supplier.component.storage.import');

        $this->input = $input;
        $this->output = $output;

        $importListener = $this->getContainer()->get('umber_firm_supplier.event.event_listener.import');
        $this->entityManager->getEventManager()->removeEventListener(Events::postFlush, $importListener);
        $this->entityManager->getEventManager()->removeEventListener(Events::postUpdate, $importListener);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $start = microtime(true);
        $this->import = $this->createImport($input, $output);

        $this->shop = $this->import->getShop();
        $this->supplier = $this->import->getSupplier();
        $data = $this->importStorage->getContent($this->import->getFilename());
        $this->format = $this->formatFactory->create($data, $this->import->getVersion());

        if (false === $this->format->isValid()) {
            $output->writeln('Format is not valid');
        }

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

        $time_elapsed_secs = microtime(true) - $start;
        $output->writeln(PHP_EOL.'Executing import time '.($time_elapsed_secs / 60));
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

    /**
     * {@inheritdoc}
     */
    public function importProducts(): void
    {
        $start = microtime(true);
        $i = 1;
        $progress = new ProgressBar($this->output, $this->format->getCountProducts());
        $progress->start();

        foreach ($this->format->buildProductStructure() as $item) {
            $progress->advance();
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
        $this->import->setImportedProducts((int) ($i-1));

        $this->entityManager->flush();
        $this->clearEntities();
        $progress->finish();

        $time_elapsed_secs = microtime(true) - $start;
        $this->output->writeln(PHP_EOL.'Executing importProducts time '.($time_elapsed_secs / 60));
    }

    /**
     * {@inheritdoc}
     */
    public function importVariants(): void
    {
        $start = microtime(true);
        $i = 1;
        $progress = new ProgressBar($this->output, $this->format->getCountVariants());
        $progress->start();

        foreach ($this->format->buildVariantStructure() as $item) {
            $variantSupplierReference = $item['variantSupplierReference'];
            $productSupplierReference = $item['productSupplierReference'];

            $progress->advance();
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
        $this->import->setImportedVariants((int) ($i-1));

        $this->entityManager->flush();
        $this->clearEntities();

        $progress->finish();

        $time_elapsed_secs = microtime(true) - $start;
        $this->output->writeln(PHP_EOL.'Executing importVariants time '.($time_elapsed_secs / 60));
    }

    /**
     * {@inheritdoc}
     */
    public function importDepartments(): void
    {
        $start = microtime(true);
        $i = 1;
        $progress = new ProgressBar($this->output, $this->format->getCountDepartments());
        $progress->start();

        foreach ($this->format->buildDepartmentStructure() as $data) {
            $variantImport = $this->findVariantImport($data['variantSupplierReference']);
            $progress->advance();
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
            $this->entityManager->persist($department);

            $this->cache['departments'][] = $department->getId()->toString();

            if (($i % self::BATCH_SIZE) === 0) {
                $this->entityManager->flush();
                $this->clearEntities();
            }
            $i++;
        }
        $this->import->setImportedDepartments((int) ($i-1));

        $this->entityManager->flush();
        $this->clearEntities();

        $progress->finish();

        $time_elapsed_secs = microtime(true) - $start;
        $this->output->writeln(PHP_EOL.'Executing importDepartments time '.($time_elapsed_secs / 60));
    }

    /**
     * @param void
     */
    private function updateProductVariantStock(): void
    {
        $start = microtime(true);

        $variantImportRepository = $this->entityManager->getRepository(VariantImport::class);
        $productVariantRepository = $this->entityManager->getRepository(ProductVariant::class);
        $count = $this->countProductVariant();
        $iterable = $variantImportRepository->variantIteratorBySupplier($this->supplier, $this->shop);
        $i = 1;
        $updated = 0;

        $progress = new ProgressBar($this->output, $count);
        $progress->start();

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

            $progress->advance();
            $i++;
        }
        $this->import->setUpdatedVariants($updated);
        $this->clearEntities();
        $progress->finish();

        $time_elapsed_secs = microtime(true) - $start;
        $this->output->writeln(PHP_EOL.'Executing updateProductVariantStock time '.($time_elapsed_secs / 60));
    }

    /**
     * @param void
     */
    private function updateProductStock(): void
    {
        $start = microtime(true);

        $productImportRepository = $this->entityManager->getRepository(ProductImport::class);
        $productRepository = $this->entityManager->getRepository(Product::class);
        $count = $this->countProduct();
        $iterable = $productImportRepository->productIteratorBySupplier($this->supplier, $this->shop);
        $i = 1;
        $updated = 0;

        $progress = new ProgressBar($this->output, $count);
        $progress->start();

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
            $progress->advance();
        }
        $this->import->setUpdatedProducts($updated);

        $this->clearEntities();
        $progress->finish();

        $time_elapsed_secs = microtime(true) - $start;
        $this->output->writeln(PHP_EOL.'Executing updateProductStock time '.($time_elapsed_secs / 60));
    }

    public function saveImportLogs()
    {
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
        $start1 = microtime(true);

        $departmentRepository = $this->entityManager->getRepository(Department::class);
        $count = $departmentRepository->resetQuantityNotIn($this->cache['departments'], $this->supplier, $this->shop);
        $this->import->setDepartmentsOutOfStock($count);

        $time_elapsed_secs = microtime(true) - $start1;
        $this->output->writeln(PHP_EOL.'Executing reset Quantity time '.($time_elapsed_secs / 60));
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return null|Import
     */
    private function createImport(InputInterface $input, OutputInterface $output): ?Import
    {
        $this->supplier = $this->getSupplier($input->getArgument('supplier'));
        $this->shop = $this->getShop($input->getArgument('shop'));

        if (null === $this->supplier || null === $this->shop) {
            $output->writeln('Supplier or Shop doesn\'t exist');

            return null;
        }

        $filePath = $input->getArgument('dir').'/'.$input->getArgument('fileName');
        $file = new UploadedFile($filePath, $input->getArgument('fileName'));

        $import = new Import();
        $import->setFile($file);
        $import->setVersion($input->getArgument('i-version'));
        $import->setSupplier($this->supplier);
        $import->setShop($this->shop);
        $this->entityManager->persist($import);
        $this->entityManager->flush();

        return $import;
    }

    /**
     * @param string $uuid
     *
     * @return null|Supplier
     */
    private function getSupplier(string $uuid): ?Supplier
    {
        if (false === Uuid::isValid($uuid)) {
            return null;
        }

        return $this->entityManager->getRepository(Supplier::class)->find($uuid);
    }

    /**
     * @param string $uuid
     *
     * @return null|Shop
     */
    private function getShop(string $uuid): ?Shop
    {
        if (false === Uuid::isValid($uuid)) {
            return null;
        }

        return $this->entityManager->getRepository(Shop::class)->find($uuid);
    }

    /**
     * @return int
     */
    private function countProductVariant(): int
    {
        $sql = 'select count(DISTINCT IDENTITY(d.productVariant)) from %s d WHERE d.supplier = \'%s\' AND d.shop = \'%s\'';
        $q = $this->entityManager->createQuery(
            sprintf(
                $sql,
                Department::class,
                $this->supplier->getId()->toString(),
                $this->shop->getId()->toString()
            )
        );

        return (int) $q->getSingleScalarResult();
    }

    /**
     * @return int
     */
    private function countProduct(): int
    {
        $sql = 'select count(DISTINCT IDENTITY(pv.product)) from %s d JOIN d.productVariant pv WHERE d.supplier = \'%s\' AND d.shop = \'%s\'';
        $q = $this->entityManager->createQuery(
            sprintf(
                $sql,
                Department::class,
                $this->supplier->getId()->toString(),
                $this->shop->getId()->toString()
            )
        );

        return (int) $q->getSingleScalarResult();
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
        $department->setPriority($this->supplier->getPosition());

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
}
