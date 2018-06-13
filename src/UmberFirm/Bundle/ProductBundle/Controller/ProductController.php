<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Hateoas\Configuration\Route;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Hateoas\Representation\PaginatedRepresentation;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\CategoryBundle\Entity\Category;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\MediaBundle\Entity\Media;
use UmberFirm\Bundle\ProductBundle\Entity\Attribute;
use UmberFirm\Bundle\ProductBundle\Entity\Department;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductImport;
use UmberFirm\Bundle\ProductBundle\Entity\ProductMedia;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariantMedia;
use UmberFirm\Bundle\ProductBundle\Entity\VariantImport;
use UmberFirm\Bundle\ProductBundle\Form\ProductType;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\Store;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;

/**
 * Class ProductController
 *
 * @package UmberFirm\Bundle\ProductBundle\Controller
 *
 * @FOS\RouteResource("product")
 * @FOS\NamePrefix("umberfirm__product__")
 */
class ProductController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of products
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @FOS\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="10",
     *     description="How many items to return."
     * )
     * @FOS\QueryParam(
     *     name="page",
     *     requirements="\d+",
     *     nullable=true
     * )
     * @FOS\QueryParam(
     *     name="q",
     *     nullable=true,
     *     description="search query string"
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Product"})
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return View
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher): View
    {
        $limit = (int) $paramFetcher->get('limit');
        $searchQuery = $paramFetcher->get('q');
        $currentPage = (int) ($paramFetcher->get('page') ?? 1);

        $pagenator = $this->get('umberfirm.component.pagenator_factory');
        $representation= $pagenator
            ->searchByQuery(Product::class, $searchQuery)
            ->getRepresentation($limit, $currentPage);

        return $this->view($representation);
    }

    /**
     * TODO: move to separate service or something similar. (d.chmelyuk@gmail.com)
     *
     * @param string $repositoryClass
     * @param int $currentPage
     * @param int $limit
     *
     * @return PaginatedRepresentation
     */
    protected function createRepresentation(string $repositoryClass, int $currentPage = 1, int $limit = 10)
    {
        $objectRepository = $this->getDoctrine()->getRepository($repositoryClass);
        $queryBuilder = $objectRepository->createQueryBuilder('c');

        $adapter = new DoctrineORMAdapter($queryBuilder, false);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($limit);
        $pagerfanta->setCurrentPage($currentPage);

        $factory = new PagerfantaFactory();
        $representation = $factory->createRepresentation(
            $pagerfanta,
            new Route(
                $this->container->get('request_stack')->getCurrentRequest()->get('_route'),
                [],
                true
            )
        );

        return $representation;
    }

    /**
     * Get specified product
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Product"})
     *
     * @param Product $product
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Product $product): View
    {
        return $this->view($product);
    }

    /**
     * Get Translations of Product
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ProductTranslation"})
     *
     * @param Product $product
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getTranslationsAction(Product $product): View
    {
        return $this->view($product->getTranslations());
    }

    /**
     * Creates a new item from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ProductBundle\Form\ProductType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Product"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->view($product, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing item from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ProductBundle\Form\ProductType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Product"})
     *
     * @param Request $request the request object
     * @param Product $product
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Product $product): View
    {
        $form = $this->createForm(ProductType::class, $product);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->view($product);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Removes an item.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @param Product $product
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Product $product): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        return $this->routeRedirectView('umberfirm__product__get_products', [], Response::HTTP_NO_CONTENT);
    }

    /**
     * @FOS\View(serializerGroups={"Export", "ExportProduct"})
     *
     * @param Request $request the request object
     * @param Shop $shop
     *
     * @return View
     */
    public function postImportAction(Request $request, Shop $shop): View
    {
        $data = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        if (true === isset($data['productImport']['supplierReference'])) {
            $productImport = $em->getRepository(ProductImport::class)->findOneBy(
                [
                    'supplierReference' => $data['productImport']['supplierReference'],
                ]
            );
            if (null !== $productImport) {
                return $this->view($productImport->getProduct(), Response::HTTP_OK);
            }
        }

        $supplier = null;
        if (null !== $data['productImport']['supplier'] || true === Uuid::isValid($data['productImport']['supplier'])) {
            $supplier = $em->find(Supplier::class, $data['productImport']['supplier']);
        }
        if (null === $supplier) {
            return $this->view(['message' => 'supplier is empty'], Response::HTTP_BAD_REQUEST);
        }
        $manufacturer = null;
        if (null !== $data['manufacturer'] || true === Uuid::isValid($data['manufacturer'])) {
            $manufacturer = $em->find(Manufacturer::class, $data['manufacturer']);
        }

        $product = new Product();
        $product->setIsElastica(false);
        $product->setManufacturer($manufacturer);
        $product->setName((string) $data['name'], 'ru');
        $product->setShortDescription((string) $data['shortDescription'], 'ru');
        $product->setDescription((string) $data['description'], 'ru');
        $product->setName((string) $data['name'], 'ua');
        $product->setShortDescription((string) $data['shortDescription'], 'ua');
        $product->setDescription((string) $data['description'], 'ua');
        $product->setOutOfStock((bool) $data['isActive']);
        $product->setPrice((float) $data['price']);
        $product->setSalePrice((float) $data['salePrice']);
        $product->setShop($shop);
        $product->setReference($data['reference']);
        foreach ($data['categories'] as $categoryId) {
            if (null === $categoryId || false === Uuid::isValid($categoryId)) {
                continue;
            }
            $category = $em->find(Category::class, $categoryId);
            if (null === $category) {
                continue;
            }
            $product->addCategory($category);
        }

        $productMedias = [];
        foreach ($data['medias'] as $filename) {
            if (null === $filename) {
                continue;
            }
            $media = $em->getRepository(Media::class)->findOneBy(['filename' => $filename]);
            if (null === $media) {
                continue;
            }
            $productMedia = new ProductMedia();
            $productMedia->setShop($shop);
            $productMedia->setProduct($product);
            $productMedia->setMedia($media);
            $em->persist($productMedia);
            $productMedias[] = $productMedia;
        }

        foreach ($data['variants'] as $variant) {
            $productVariant = new ProductVariant();
            $productVariant->setShop($shop);
            $productVariant->setProduct($product);

            $price = null;
            $salePrice = null;
            foreach ($variant['departments'] as $departmentData){
                $store = null;
                if (null !== $departmentData['store'] || true === Uuid::isValid($departmentData['store'])) {
                    $store = $em->find(Store::class, $departmentData['store']);
                }
                $department = new Department();
                $department->setSupplier($supplier);
                $department->setShop($shop);
                $department->setStore($store);
                $department->setQuantity((int) $departmentData['quantity']);
                $department->setArticle((string) $departmentData['article']);
                $department->setEan13((string) $departmentData['ean13']);
                $department->setSalePrice((float) $departmentData['salePrice']);
                $department->setPrice((float) $departmentData['price']);

                $em->persist($department);
                $productVariant->addDepartment($department);

                $price = ($price < (float) $departmentData['price']) ? (float) $departmentData['price'] : (float) $price;
                $salePrice = ($salePrice < (float) $departmentData['salePrice']) ? (float) $departmentData['price'] : (float) $salePrice;
            }
            $productVariant->setPrice($price);
            $productVariant->setSalePrice($salePrice);

            foreach ($variant['attributes'] as $attributeId) {
                if (null === $attributeId || false === Uuid::isValid($attributeId)) {
                    continue;
                }
                $attribute = $em->find(Attribute::class, $attributeId);
                $productVariant->addProductVariantAttribute($attribute);
            }

            foreach ($productMedias as $productMedia) {
                $productVariantMedia = new ProductVariantMedia();
                $productVariantMedia->setProductVariant($productVariant);
                $productVariantMedia->setProductMedia($productMedia);
                $em->persist($productVariantMedia);
            }

            $em->persist($productVariant);

            if (false === empty($variant['offer_id'])) {
                $variantImport = new VariantImport();
                $variantImport->setShop($shop);
                $variantImport->setSupplier($supplier);
                $variantImport->setProductVariant($productVariant);
                $variantImport->setSupplierReference($variant['offer_id']);
                $em->persist($variantImport);
            }
        }

        $productImport = new ProductImport();
        $productImport->setSupplier($supplier);
        $productImport->setShop($shop);
        $productImport->setProduct($product);
        $productImport->setSupplierReference($data['productImport']['supplierReference']);
        $em->persist($productImport);

        $product->mergeNewTranslations();
        $em->persist($product);

        $em->flush();

        return $this->view($product, Response::HTTP_CREATED);
    }
}
