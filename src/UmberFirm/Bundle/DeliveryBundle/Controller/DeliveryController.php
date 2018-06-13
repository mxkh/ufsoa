<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\DeliveryBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\DeliveryBundle\Entity\Delivery;
use UmberFirm\Bundle\DeliveryBundle\Form\DeliveryType;

/**
 * Class DeliveryController
 *
 * @package UmberFirm\Bundle\DeliveryBundle\Controller
 *
 * @FOS\RouteResource("delivery")
 * @FOS\NamePrefix("umberfirm__delivery__")
 */
class DeliveryController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get deliveries.
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
     * @FOS\View(serializerGroups={"Default", "Delivery"})
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
            ->searchByQuery(Delivery::class, $searchQuery)
            ->getRepresentation($limit, $currentPage);

        return $this->view($representation);
    }

    /**
     * Get specified delivery
     *
     * @ApiDoc(
     *      resource = true,
     *      statusCodes = {
     *          200 = "Returned when successful",
     *          404 = "Returned when the resource not found"
     *      }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Delivery"})
     *
     * @param Delivery $delivery
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Delivery $delivery): View
    {
        return $this->view($delivery);
    }

    /**
     * Get Translations of Delivery
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "DeliveryTranslation"})
     *
     * @param Delivery $delivery
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getTranslationsAction(Delivery $delivery): View
    {
        return $this->view($delivery->getTranslations());
    }

    /**
     * Creates a new delivery from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\DeliveryBundle\Form\DeliveryType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Delivery"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $delivery = new Delivery();
        $form = $this->createForm(DeliveryType::class, $delivery);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($delivery);
            $em->flush();

            return $this->view($delivery, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing delivery from the submitted data or create a new one
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\DeliveryBundle\Form\DeliveryType",
     *   statusCodes = {
     *     200 = "Returned when a new resource is updated",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Delivery"})
     *
     * @param Request $request
     * @param Delivery $delivery
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Delivery $delivery): View
    {
        $form = $this->createForm(DeliveryType::class, $delivery);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($delivery);
            $em->flush();

            return $this->view($delivery);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Removes a Delivery.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @param Delivery $delivery
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Delivery $delivery): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($delivery);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__delivery__get_deliveries',
            [],
            Response::HTTP_NO_CONTENT
        );
    }
}
