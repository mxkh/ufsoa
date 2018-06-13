<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Category;

use Doctrine\Common\Persistence\ObjectRepository;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\CategoryBundle\Entity\Category;
use UmberFirm\Bundle\CategoryBundle\Repository\CategoryNestedTreeRepositoryInterface;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Repository\ProductRepository;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class CategoryController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Category;
 *
 * @FOS\RouteResource("category")
 * @FOS\NamePrefix("umberfirm__public__")
 */
class CategoryController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of categories.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicCategories"})
     *
     * @return View
     */
    public function cgetAction(): View
    {
        /** @var Shop $shop */
        $shop = $this->getUser();

        /** @var CategoryNestedTreeRepositoryInterface|ObjectRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $collection = $repository->getRootNodesByShop($shop);

        return $this->view($collection);
    }

    /**
     * Get specified category.
     *
     * @ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *       200 = "Returned when successful",
     *       404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicCategory"})
     *
     * @param Category $category
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Category $category): View
    {
        /** @var Shop $shop */
        $shop = $this->getUser();

        /** @var CategoryNestedTreeRepositoryInterface|ObjectRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $entity = $repository->findOneBy(['id' => $category, 'shop' => $shop]);

        return $this->view($entity);
    }

    /**
     * Get list of products
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\QueryParam(
     *     name="offset",
     *     requirements="\d+",
     *     nullable=true,
     *     description="Offset from which to start listing items."
     * )
     * @FOS\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="10",
     *     description="How many items to return."
     * )
     *
     * @FOS\View(serializerGroups={"PublicService",  "PublicCategoryProducts"})
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param Category $category
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getProductsAction(ParamFetcherInterface $paramFetcher, Category $category): View
    {
        /** @var Shop $shop */
        $shop = $this->getUser();

        $offset = $paramFetcher->get('offset');
        $limit = $paramFetcher->get('limit');

        /** @var ProductRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $collection = $repository->findByShopInCategory($shop, $category, (int) $offset, (int) $limit);

        return $this->view($collection);
    }
}
