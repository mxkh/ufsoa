<?php

namespace UmberFirm\Bundle\CustomerBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerGroup;
use UmberFirm\Bundle\CustomerBundle\Form\CustomerGroupType;

/**
 * Class CustomerGroupController
 *
 * @package UmberFirm\Bundle\CustomerBundle\Controller
 *
 * @FOS\RouteResource("customer-group")
 * @FOS\NamePrefix("umberfirm__customer__")
 */
class CustomerGroupController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of customer groups
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
     * @FOS\View(serializerGroups={"Default", "CustomerGroup"})
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
            ->searchByQuery(CustomerGroup::class, $searchQuery)
            ->getRepresentation($limit, $currentPage);

        return $this->view($representation);
    }

    /**
     * Get specified customer group
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "CustomerGroup"})
     *
     * @param CustomerGroup $customerGroup
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(CustomerGroup $customerGroup): View
    {
        return $this->view($customerGroup);
    }

    /**
     * Get Translations of CustomerGroup
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "CustomerGroupTranslation"})
     *
     * @param CustomerGroup $customerGroup
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getTranslationsAction(CustomerGroup $customerGroup): View
    {
        return $this->view($customerGroup->getTranslations());
    }

    /**
     * Creates a new item from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\CustomerBundle\Form\CustomerGroupType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     * @FOS\View(serializerGroups={"Default", "CustomerGroup"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $customerGroup = new CustomerGroup();
        $form = $this->createForm(CustomerGroupType::class, $customerGroup);
        $data = json_decode($request->getContent(), true);

        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($customerGroup);
            $em->flush();

            return $this->view($customerGroup, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing item from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\CustomerBundle\Form\CustomerGroupType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     * @FOS\View(serializerGroups={"Default", "CustomerGroup"})
     *
     * @param Request $request the request object
     * @param CustomerGroup $customerGroup
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, CustomerGroup $customerGroup): View
    {
        $form = $this->createForm(CustomerGroupType::class, $customerGroup);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($customerGroup);
            $em->flush();

            return $this->view($customerGroup, Response::HTTP_OK);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Removes an item.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes={
     *     204="Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @param CustomerGroup $customerGroup
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(CustomerGroup $customerGroup): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($customerGroup);
        $em->flush();

        return $this->routeRedirectView('umberfirm__customer__get_customer-groups', [], Response::HTTP_NO_CONTENT);
    }
}
