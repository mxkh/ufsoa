<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use UmberFirm\Bundle\ShopBundle\Entity\ContactEnum;
use UmberFirm\Bundle\ShopBundle\Form\ContactEnumType;

/**
 * @FOS\RouteResource("contact-enum")
 * @FOS\NamePrefix("umberfirm__shop__")
 */
class ContactEnumController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of contact enumerations
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
     * @FOS\View(serializerGroups={"Default", "ContactEnum"})
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
            ->searchByQuery(ContactEnum::class, $searchQuery)
            ->getRepresentation($limit, $currentPage);

        return $this->view($representation);
    }

    /**
     * Get specified contact enum
     *
     * @ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *       200 = "Returned when successful",
     *       404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ContactEnum"})
     *
     * @param ContactEnum $contactEnum
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(ContactEnum $contactEnum): View
    {
        return $this->view($contactEnum);
    }

    /**
     * Get Translations of ContactEnum
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ContactEnumTranslation"})
     *
     * @param ContactEnum $contactEnum
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getTranslationsAction(ContactEnum $contactEnum): View
    {
        return $this->view($contactEnum->getTranslations());
    }

    /**
     * Creates a new Shop from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ShopBundle\Form\ContactEnumType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     * @FOS\View(serializerGroups={"Default", "ContactEnum"})
     *
     * @param Request $request
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $contactEnum = new ContactEnum();

        $form = $this->createForm(ContactEnumType::class, $contactEnum);
        $data = json_decode($request->getContent(), true);

        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contactEnum);
            $em->flush();

            return $this->view($contactEnum, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing contact enums from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ShopBundle\Form\ContactEnumType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ContactEnum"})
     *
     * @param Request $request
     * @param ContactEnum $contactEnum
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, ContactEnum $contactEnum): View
    {
        $form = $this->createForm(ContactEnumType::class, $contactEnum);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contactEnum);
            $em->flush();

            return $this->view($contactEnum);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }
}
