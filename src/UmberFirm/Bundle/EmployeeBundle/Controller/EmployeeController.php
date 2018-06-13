<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\EmployeeBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\EmployeeBundle\Entity\Employee;
use UmberFirm\Bundle\EmployeeBundle\Form\EmployeeLoginType;
use UmberFirm\Bundle\EmployeeBundle\Form\EmployeeType;

/**
 * Class EmployeeController
 *
 * @package UmberFirm\Bundle\EmployeeBundle\Controller
 *
 * @FOS\RouteResource("employee")
 * @FOS\NamePrefix("umberfirm__employee__")
 */
class EmployeeController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of employees
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
     * @FOS\View(serializerGroups={"Default", "Employee"})
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
            ->searchByQuery(Employee::class, $searchQuery)
            ->getRepresentation($limit, $currentPage);

        return $this->view($representation);
    }

    /**
     * Get specified employee
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Employee"})
     *
     * @param Employee $employee
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Employee $employee): View
    {
        return $this->view($employee);
    }

    /**
     * Creates a new item from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\EmployeeBundle\Form\EmployeeType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Employee"})
     *
     * @param Request $request the request object
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $employee = new Employee();
        $form = $this->createForm(EmployeeType::class, $employee);
        $data = json_decode($request->getContent(), true);

        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($employee);
            $em->flush();

            return $this->view($employee, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing item from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\EmployeeBundle\Form\EmployeeType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Employee"})
     *
     * @param Request $request the request object
     * @param Employee $employee
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Employee $employee): View
    {
        $form = $this->createForm(EmployeeType::class, $employee);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($employee);
            $em->flush();

            return $this->view($employee);
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
     * @param Employee $employee
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Employee $employee): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($employee);
        $em->flush();

        return $this->routeRedirectView('umberfirm__employee__get_employees', [], Response::HTTP_NO_CONTENT);
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\EmployeeBundle\Form\EmployeeLoginType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postLoginAction(Request $request): View
    {
        $form = $this->createForm(EmployeeLoginType::class);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (false === $form->isValid()) {
            return $this->view($form, Response::HTTP_BAD_REQUEST);
        }
        $email = (string) $form->get('email')->getData();
        $password = (string) $form->get('password')->getData();

        $loginManager = $this->get('umberfirm.employee.component.manager.login_manager');
        $employee = $loginManager->loadEmployeeByEmail($email);

        if (null !== $employee && true === $loginManager->checkEmployeePassword($employee, $password)) {
            $loginManager->login($employee);
            $tokenStorage = $this->get('security.token_storage');

            return $this->view(['token' => $tokenStorage->getToken()->getCredentials()]);
        }

        $form->get('email')->addError(new FormError('Email or password is incorrect'));

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }
}
