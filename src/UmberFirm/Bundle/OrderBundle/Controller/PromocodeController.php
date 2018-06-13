<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\OrderBundle\Entity\Promocode;
use UmberFirm\Bundle\OrderBundle\Form\PromocodeType;

/**
 * Class PromocodeController
 *
 * @package UmberFirm\Bundle\OrderBundle\Controller
 *
 * @FOS\RouteResource("promocode")
 * @FOS\NamePrefix("umberfirm__order__")
 */
class PromocodeController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of Promocodes
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
     * @FOS\View(serializerGroups={"Default", "Promocode"})
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
            ->searchByQuery(Promocode::class, $searchQuery)
            ->getRepresentation($limit, $currentPage);

        return $this->view($representation);
    }

    /**
     * Get specified Promocode
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Promocode"})
     *
     * @param Promocode $promocode
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Promocode $promocode): View
    {
        return $this->view($promocode);
    }

    /**
     * Creates a new item from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\OrderBundle\Form\PromocodeType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Promocode"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $promocode = new Promocode();
        $form = $this->createForm(PromocodeType::class, $promocode);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($promocode);
            $em->flush();

            return $this->view($promocode, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing item from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\OrderBundle\Form\PromocodeType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Promocode"})
     *
     * @param Request $request the request object
     * @param Promocode $promocode
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Promocode $promocode): View
    {
        $form = $this->createForm(PromocodeType::class, $promocode);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($promocode);
            $em->flush();

            return $this->view($promocode);
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
     * @param Promocode $promocode
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Promocode $promocode): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($promocode);
        $em->flush();

        return $this->routeRedirectView('umberfirm__order__get_promocodes', [], Response::HTTP_NO_CONTENT);
    }

    /**
     * Generates promocode
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @return View
     */
    public function getGenerateAction(): View
    {
        $promoCode = $this->get('umberfirm.order.component.data_transfer_object.factory.promo_code')->create(
            $this->get('umberfirm.order.component.generator.promo_code')->generate()
        );

        return $this->view($promoCode);
    }
}
