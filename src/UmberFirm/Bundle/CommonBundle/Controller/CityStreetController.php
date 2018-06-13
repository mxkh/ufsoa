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
use UmberFirm\Bundle\CommonBundle\Entity\Street;

/**
 * Class CityStreetController
 *
 * @package UmberFirm\Bundle\CommonBundle\Controller
 *
 * @FOS\RouteResource("streets")
 * @FOS\NamePrefix("umberfirm__common__")
 */
class CityStreetController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get autocompleted list of streets filtered by city and search query string if any
     *
     * @ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         404 = "Returned when city not found"
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
     * @FOS\View(serializerGroups={"Default", "Street"})
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param City $city
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function searchSuggestionsAction(ParamFetcherInterface $paramFetcher, City $city): View
    {
        $limit = (int) $paramFetcher->get('limit');
        $searchQuery = $paramFetcher->get('q');
        $currentPage = (int) ($paramFetcher->get('page') ?? 1);

        $pagenator = $this->get('umberfirm.component.pagenator_factory');
        $pagenator->searchByQuery(Street::class, $searchQuery)
            ->getQueryBuilder()
            ->andWhere('street.city = :city')
            ->setParameter('city', $city);

        $representation = $pagenator->getRepresentation(
            $limit,
            $currentPage,
            [
                'city' => $city->getId()->toString(),
            ]
        );

        return $this->view($representation);
    }
}
