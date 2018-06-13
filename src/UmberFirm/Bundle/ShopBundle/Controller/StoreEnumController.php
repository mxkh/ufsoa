<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\ShopBundle\Entity\StoreEnum;
use UmberFirm\Bundle\ShopBundle\Form\StoreEnumType;

/**
 * @FOS\RouteResource("store-enum")
 * @FOS\NamePrefix("umberfirm__shop__")
 */
class StoreEnumController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of store enumerations.
     *
     * @ApiDoc(
     *      resource = true,
     *      statusCodes = {
     *          200 = "Returned when successful"
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
     * @FOS\View(serializerGroups={"Default", "StoreEnum"})
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
            ->searchByQuery(StoreEnum::class, $searchQuery)
            ->getRepresentation($limit, $currentPage);

        return $this->view($representation);
    }

    /**
     * Get specified store enum.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "StoreEnum"})
     *
     * @param StoreEnum $storeEnum
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(StoreEnum $storeEnum): View
    {
        return $this->view($storeEnum);
    }

    /**
     * Get Translations of StoreEnum
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "StoreEnumTranslation"})
     *
     * @param StoreEnum $storeEnum
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getTranslationsAction(StoreEnum $storeEnum): View
    {
        return $this->view($storeEnum->getTranslations());
    }

    /**
     * Creates a new store enum from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ShopBundle\Form\StoreEnumType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "StoreEnum"})
     *
     * @param Request $request
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $storeEnum = new StoreEnum();

        $form = $this->createForm(StoreEnumType::class, $storeEnum);
        $data = json_decode($request->getContent(), true);

        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($storeEnum);
            $em->flush();

            return $this->view($storeEnum, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing store enum from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ShopBundle\Form\StoreEnumType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "StoreEnum"})
     *
     * @param Request $request
     * @param StoreEnum $storeEnum
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, StoreEnum $storeEnum): View
    {
        $form = $this->createForm(StoreEnumType::class, $storeEnum);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($storeEnum);
            $em->flush();

            return $this->view($storeEnum);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }
}
