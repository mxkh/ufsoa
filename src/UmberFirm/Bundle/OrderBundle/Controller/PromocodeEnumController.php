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
use UmberFirm\Bundle\OrderBundle\Entity\PromocodeEnum;
use UmberFirm\Bundle\OrderBundle\Form\PromocodeEnumType;

/**
 * Class PromocodeEnumController
 *
 * @package UmberFirm\Bundle\OrderBundle\Controller
 *
 * @FOS\RouteResource("promocode-enum")
 * @FOS\NamePrefix("umberfirm__order__")
 */
class PromocodeEnumController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of PromocodeEnums
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
     * @FOS\View(serializerGroups={"Default", "PromocodeEnum"})
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
            ->searchByQuery(PromocodeEnum::class, $searchQuery)
            ->getRepresentation($limit, $currentPage);

        return $this->view($representation);
    }

    /**
     * Get specified PromocodeEnum
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "PromocodeEnum"})
     *
     * @param PromocodeEnum $promocodeEnum
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(PromocodeEnum $promocodeEnum): View
    {
        return $this->view($promocodeEnum);
    }

    /**
     * Get Translations of Gender
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "PromocodeEnumTranslation"})
     *
     * @param PromocodeEnum $promocodeEnum
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getTranslationsAction(PromocodeEnum $promocodeEnum): View
    {
        return $this->view($promocodeEnum->getTranslations());
    }

    /**
     * Creates a new item from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\OrderBundle\Form\PromocodeEnumType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "PromocodeEnum"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $promocodeEnum = new PromocodeEnum();
        $form = $this->createForm(PromocodeEnumType::class, $promocodeEnum);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($promocodeEnum);
            $em->flush();

            return $this->view($promocodeEnum, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing item from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\OrderBundle\Form\PromocodeEnumType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "PromocodeEnum"})
     *
     * @param Request $request the request object
     * @param PromocodeEnum $promocodeEnum
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, PromocodeEnum $promocodeEnum): View
    {
        $form = $this->createForm(PromocodeEnumType::class, $promocodeEnum);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($promocodeEnum);
            $em->flush();

            return $this->view($promocodeEnum);
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
     * @param PromocodeEnum $promocodeEnum
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(PromocodeEnum $promocodeEnum): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($promocodeEnum);
        $em->flush();

        return $this->routeRedirectView('umberfirm__order__get_promocode-enums', [], Response::HTTP_NO_CONTENT);
    }
}
