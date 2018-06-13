<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\CommonBundle\Entity\City;

/**
 * Class CityController
 *
 * @package UmberFirm\Bundle\CommonBundle\Controller
 *
 * @FOS\RouteResource("city")
 * @FOS\NamePrefix("umberfirm__common__")
 */
class CityController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get specified city
     *
     * @ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         404 = "Returned when the resource not found"
     *     }
     * )
     * @FOS\View(serializerGroups={"Default", "City"})
     *
     * @param City $city
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(City $city): View
    {
        return $this->view($city);
    }

    /**
     * Get autocompleted list of cities filtered by search query string if any
     *
     * @ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *         200 = "Returned when successful"
     *     }
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
     * @FOS\View(serializerGroups={"Default", "City"})
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return View
     */
    public function searchSuggestionsAction(ParamFetcherInterface $paramFetcher): View
    {
        $limit = (int) $paramFetcher->get('limit');
        $searchQuery = $paramFetcher->get('q');
        $currentPage = (int) ($paramFetcher->get('page') ?? 1);

        $pagenator = $this->get('umberfirm.component.pagenator_factory');
        $representation= $pagenator
            ->searchByQuery(City::class, $searchQuery)
            ->getRepresentation($limit, $currentPage);

        return $this->view($representation);
    }
}
