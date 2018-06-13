<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\ShopBundle\Entity\SettingsAttribute;
use Symfony\Component\HttpFoundation\Request;
use UmberFirm\Bundle\ShopBundle\Form\SettingsAttributeType;

/**
 * @FOS\RouteResource("settings-attribute")
 * @FOS\NamePrefix("umberfirm__shop__")
 */
class SettingsAttributeController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of SettingsAttributes
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
     * @FOS\View(serializerGroups={"Default", "SettingsAttribute"})
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
            ->searchByQuery(SettingsAttribute::class, $searchQuery)
            ->getRepresentation($limit, $currentPage);

        return $this->view($representation);
    }

    /**
     * Get specified Settings Attribute
     *
     * @ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *       200 = "Returned when successful",
     *       404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "SettingsAttribute"})
     *
     * @param SettingsAttribute $attribute
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(SettingsAttribute $attribute): View
    {
        return $this->view($attribute);
    }

    /**
     * Create an settingsAttribute
     *
     * @param Request $request
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirmShopBundle\Form\SettingsAttributeType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "SettingsAttribute"})
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $attribute = new SettingsAttribute();

        $form = $this->createForm(SettingsAttributeType::class, $attribute);
        $data = json_decode($request->getContent(), true);

        $form->submit($data);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($attribute);
            $em->flush();

            return $this->view($attribute, Response::HTTP_CREATED);
        }

        return $this->view($attribute, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing shop from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirmShopBundle\Form\SettingsAttributeType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "SettingsAttribute"})
     *
     * @param Request $request
     * @param SettingsAttribute $attribute
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, SettingsAttribute $attribute): View
    {
        $form = $this->createForm(SettingsAttributeType::class, $attribute);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($attribute);
            $em->flush();

            return $this->view($attribute);
        }

        return $this->view($attribute, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Remove a settings attribute by id
     *
     * @param SettingsAttribute $attribute
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @throws NotFoundHttpException
     *
     * @return \FOS\RestBundle\View\View
     */
    public function deleteAction(SettingsAttribute $attribute): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($attribute);
        $em->flush();

        return $this->routeRedirectView('umberfirm__shop__get_settings-attributes', [], Response::HTTP_NO_CONTENT);
    }
}
