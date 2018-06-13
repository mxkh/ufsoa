<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Common;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\CommonBundle\Entity\City;
use UmberFirm\Bundle\CommonBundle\Repository\CityRepository;

/**
 * Class CityController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Common
 *
 * @FOS\RouteResource("city")
 * @FOS\NamePrefix("umberfirm__public__")
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
     * @FOS\View(serializerGroups={"PublicService", "PublicCity"})
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
     *     nullable=true,
     *     description="How many items to return. If zero|null given, all results returned."
     * )
     * @FOS\QueryParam(
     *     name="q",
     *     nullable=true,
     *     description="search query string for autocomplete"
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicCity"})
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return View
     */
    public function searchSuggestionsAction(ParamFetcherInterface $paramFetcher): View
    {
        $limit = (int) $paramFetcher->get('limit');
        $query = (string) $paramFetcher->get('q');

        /** @var CityRepository $repository */
        $repository = $this->getDoctrine()->getRepository(City::class);
        $collection = $repository->findSuggestions($query, $limit);

        return $this->view($collection);
    }
}
