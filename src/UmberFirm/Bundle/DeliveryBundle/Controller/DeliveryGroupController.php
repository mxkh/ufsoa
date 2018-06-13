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
use UmberFirm\Bundle\DeliveryBundle\Entity\DeliveryGroup;
use UmberFirm\Bundle\DeliveryBundle\Form\DeliveryGroupType;

/**
 * Class DeliveryGroupController
 *
 * @package UmberFirm\Bundle\DeliveryBundle\Controller
 *
 * @FOS\RouteResource("delivery-group")
 * @FOS\NamePrefix("umberfirm__delivery__")
 */
class DeliveryGroupController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get delivery groups.
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
     * @FOS\View(serializerGroups={"Default", "DeliveryGroup"})
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
            ->searchByQuery(DeliveryGroup::class, $searchQuery)
            ->getRepresentation($limit, $currentPage);

        return $this->view($representation);
    }

    /**
     * Get specified delivery group
     *
     * @ApiDoc(
     *      resource = true,
     *      statusCodes = {
     *          200 = "Returned when successful",
     *          404 = "Returned when the resource not found"
     *      }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "DeliveryGroup"})
     *
     * @param DeliveryGroup $deliveryGroup
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(DeliveryGroup $deliveryGroup): View
    {
        return $this->view($deliveryGroup);
    }

    /**
     * Get Translations of Delivery groups
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "DeliveryGroupTranslation"})
     *
     * @param DeliveryGroup $deliveryGroup
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getTranslationsAction(DeliveryGroup $deliveryGroup): View
    {
        return $this->view($deliveryGroup->getTranslations());
    }

    /**
     * Creates a new delivery group from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\DeliveryBundle\Form\DeliveryGroupType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "DeliveryGroup"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $deliveryGroup = new DeliveryGroup();
        $form = $this->createForm(DeliveryGroupType::class, $deliveryGroup);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($deliveryGroup);
            $em->flush();

            return $this->view($deliveryGroup, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing delivery group from the submitted data or create a new one
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\DeliveryBundle\Form\DeliveryGroupType",
     *   statusCodes = {
     *     200 = "Returned when a new resource is updated",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "DeliveryGroup"})
     *
     * @param Request $request
     * @param DeliveryGroup $deliveryGroup
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, DeliveryGroup $deliveryGroup): View
    {
        $form = $this->createForm(DeliveryGroupType::class, $deliveryGroup);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($deliveryGroup);
            $em->flush();

            return $this->view($deliveryGroup);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Removes a delivery group.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @param DeliveryGroup $deliveryGroup
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(DeliveryGroup $deliveryGroup): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($deliveryGroup);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__delivery__get_delivery-groups',
            [],
            Response::HTTP_NO_CONTENT
        );
    }
}
