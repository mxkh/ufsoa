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
use UmberFirm\Bundle\CommonBundle\Entity\Branch;
use UmberFirm\Bundle\CommonBundle\Entity\City;
use UmberFirm\Bundle\CommonBundle\Repository\BranchRepository;

/**
 * Class BranchController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Common
 *
 * @FOS\RouteResource("branches")
 * @FOS\NamePrefix("umberfirm__public__")
 */
class CityBranchController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get autocompleted list of branches filtered by city and search query string if any
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
     *     nullable=true,
     *     description="How many items to return."
     * )
     * @FOS\QueryParam(
     *     name="q",
     *     nullable=true,
     *     description="search query string for autocomplete"
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicBranch"})
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
        $limit = null === $paramFetcher->get('limit') ? null : (int) $paramFetcher->get('limit');
        $query = (string) $paramFetcher->get('q');

        /** @var BranchRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Branch::class);
        $collection = $repository->findSuggestionsByCity($city, $query, $limit);

        return $this->view($collection);
    }
}
