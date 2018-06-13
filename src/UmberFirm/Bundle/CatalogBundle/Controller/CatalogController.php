<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CatalogBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class DefaultController
 *
 * @package UmberFirm\Bundle\CatalogBundle\Controller
 *
 * @FOS\RouteResource("catalog")
 * @FOS\NamePrefix("umberfirm__catalog__")
 */
class CatalogController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get catalog objects.
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
     *     nullable=true,
     *     description="How many items to return."
     * )
     * @FOS\QueryParam(
     *     name="page",
     *     requirements="\d+",
     *     nullable=true,
     *     description="Current page."
     * )
     * @FOS\QueryParam(
     *     name="q",
     *     nullable=true,
     *     description="Search query string (full text search)."
     * )
     * @FOS\QueryParam(
     *     name="categories",
     *     nullable=true,
     *     description="Filter by category slugs"
     * )
     * @FOS\QueryParam(
     *     name="filter",
     *     nullable=true,
     *     description="Attributes to filter."
     * )
     * @FOS\QueryParam(
     *     name="terms",
     *     nullable=true,
     *     description="Attributes to filter. Example: ?terms[][is_active]=true&terms[][is_out_of_stock]=false"
     * )
     * @FOS\QueryParam(
     *     name="sort",
     *     nullable=true,
     *     description="Sorting parameters, allowed vars = asc or desc."
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Catalog"})
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return View
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher): View
    {
        $perPage = (int) $paramFetcher->get('limit');
        $currentPage = (int) $paramFetcher->get('page');
        $searchPhrase = (string) $paramFetcher->get('q');
        $attributes = (array) $paramFetcher->get('filter');
        $sort = (array) $paramFetcher->get('sort');
        $categories = (array) $paramFetcher->get('categories');
        $terms = (array) $paramFetcher->get('terms');

        $catalog = $this->container->get('umberfirm.component.catalog');

        $result = $catalog
            ->setCurrentPage($currentPage)
            ->setPerPage($perPage)
            ->findPaginated($categories, $attributes, $sort, $terms, $searchPhrase);

        return $this->view($result);
    }

    /**
     * Get catalog aggregations.
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
     *     nullable=true,
     *     description="How many items to return."
     * )
     * @FOS\QueryParam(
     *     name="page",
     *     requirements="\d+",
     *     nullable=true,
     *     description="Current page."
     * )
     * @FOS\QueryParam(
     *     name="q",
     *     nullable=true,
     *     description="Search query string (full text search)."
     * )
     * @FOS\QueryParam(
     *     name="categories",
     *     nullable=true,
     *     description="Filter by category slugs"
     * )
     * @FOS\QueryParam(
     *     name="filter",
     *     nullable=true,
     *     description="Attributes to filter."
     * )
     * @FOS\QueryParam(
     *     name="terms",
     *     nullable=true,
     *     description="Attributes to filter. Example: ?terms[][is_active]=true&terms[][is_out_of_stock]=false"
     * )
     * @FOS\QueryParam(
     *     name="sort",
     *     nullable=true,
     *     description="Sorting parameters, allowed vars = asc or desc."
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Catalog"})
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return View
     */
    public function getAggregationsAction(ParamFetcherInterface $paramFetcher): View
    {
        $perPage = (int) $paramFetcher->get('limit');
        $currentPage = (int) $paramFetcher->get('page');
        $searchPhrase = (string) $paramFetcher->get('q');
        $attributes = (array) $paramFetcher->get('filter');
        $sort = (array) $paramFetcher->get('sort');
        $categories = (array) $paramFetcher->get('categories');
        $terms = (array) $paramFetcher->get('terms');

        $catalog = $this->container->get('umberfirm.component.catalog');

        $result = $catalog
            ->setCurrentPage($currentPage)
            ->setPerPage($perPage)
            ->getAggregations($categories, $attributes, $sort, $terms, $searchPhrase);

        return $this->view($result);
    }
}
