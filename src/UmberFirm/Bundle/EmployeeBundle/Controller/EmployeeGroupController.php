<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\EmployeeBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\EmployeeBundle\Entity\EmployeeGroup;
use UmberFirm\Bundle\EmployeeBundle\Form\EmployeeGroupType;

/**
 * Class EmployeeGroupController
 *
 * @package UmberFirm\Bundle\EmployeeBundle\Controller
 *
 * @FOS\RouteResource("employee-group")
 * @FOS\NamePrefix("umberfirm__employee__")
 */
class EmployeeGroupController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of EmployeeGroups
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
     * @FOS\View(serializerGroups={"Default", "EmployeeGroup"})
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
            ->searchByQuery(EmployeeGroup::class, $searchQuery)
            ->getRepresentation($limit, $currentPage);

        return $this->view($representation);
    }

    /**
     * Get specified EmployeeGroup
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "EmployeeGroup"})
     *
     * @param EmployeeGroup $employeeGroup
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(EmployeeGroup $employeeGroup): View
    {
        return $this->view($employeeGroup);
    }

    /**
     * Get Translations of EmployeeGroup
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "EmployeeGroupTranslation"})
     *
     * @param EmployeeGroup $employeeGroup
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getTranslationsAction(EmployeeGroup $employeeGroup): View
    {
        return $this->view($employeeGroup->getTranslations());
    }

    /**
     * Creates a new Employee from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirmCommonBundle\Form\EmployeeGroupType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "EmployeeGroup"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request)
    {
        $employeeGroup = new EmployeeGroup();
        $form = $this->createForm(EmployeeGroupType::class, $employeeGroup);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($employeeGroup);
            $em->flush();

            return $this->view($employeeGroup, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing employee group from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirmEmployeeBundle\Form\EmployeeGroupType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "EmployeeGroup"})
     *
     * @param Request $request the request object
     * @param EmployeeGroup $employeeGroup
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, EmployeeGroup $employeeGroup): View
    {
        $form = $this->createForm(EmployeeGroupType::class, $employeeGroup);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($employeeGroup);
            $em->flush();

            return $this->view($employeeGroup);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Removes a EmployeeGroup.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @param EmployeeGroup $employeeGroup
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(EmployeeGroup $employeeGroup): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($employeeGroup);
        $em->flush();

        return $this->routeRedirectView('umberfirm__employee__get_employee-groups', [], Response::HTTP_NO_CONTENT);
    }
}
