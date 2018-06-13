<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Controller;

use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use UmberFirm\Bundle\ShopBundle\Entity\Geolocation;
use UmberFirm\Bundle\ShopBundle\Form\GeolocationType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @FOS\RouteResource("geolocation")
 * @FOS\NamePrefix("umberfirm__shop__")
 */
class GeolocationController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of geolocations
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
     * @FOS\View(serializerGroups={"Default", "Geolocation"})
     *
     * @param ParamFetcherInterface $paramFetcher param fetcher service
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
            ->searchByQuery(Geolocation::class, $searchQuery)
            ->getRepresentation($limit, $currentPage);

        return $this->view($representation);
    }

    /**
     * Get specified contact
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Geolocation"})
     *
     * @param Geolocation $geolocation
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Geolocation $geolocation): View
    {
        return $this->view($geolocation);
    }

    /**
     * Creates a new coordinates from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirmShopBundle\Form\GeolocationType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Geolocation"})
     *
     * @param Request $request
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $geolocation = new Geolocation();

        $form = $this->createForm(GeolocationType::class, $geolocation);
        $data = json_decode($request->getContent(), true);

        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($geolocation);
            $em->flush();

            return $this->view($geolocation, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing coordinates from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirmShopBundle\Form\GeolocationType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Geolocation"})
     *
     * @param Request $request
     * @param Geolocation $geolocation
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Geolocation $geolocation): View
    {
        $form = $this->createForm(GeolocationType::class, $geolocation);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($geolocation);
            $em->flush();

            return $this->view($geolocation);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Remove a geolocation
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful"
     *   }
     * )
     *
     * @param Geolocation $geolocation
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Geolocation $geolocation): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($geolocation);
        $em->flush();

        return $this->routeRedirectView('umberfirm__shop__get_geolocation', [], Response::HTTP_NO_CONTENT);
    }
}
