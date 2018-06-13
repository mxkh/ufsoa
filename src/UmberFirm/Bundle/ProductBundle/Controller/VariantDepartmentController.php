<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\ProductBundle\Entity\Department;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\ProductBundle\Form\DepartmentType;

/**
 * Class VariantDepartmentController
 *
 * @package UmberFirm\Bundle\ProductBundle\ProductVariant\Controller
 *
 * @FOS\NamePrefix("umberfirm__product__")
 */
class VariantDepartmentController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Department"})
     *
     * @param ProductVariant $productVariant
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function cgetAction(ProductVariant $productVariant): View
    {
        $repository = $this->getDoctrine()->getRepository(Department::class);
        $items = $repository->findBy(['productVariant' => $productVariant]);

        return $this->view($items);
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Department"})
     *
     * @param ProductVariant $productVariant
     * @param Department $department
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(ProductVariant $productVariant, Department $department): View
    {
        if ($productVariant->getId()->toString() !== $department->getProductVariant()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        return $this->view($department);
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ProductVariantBundle\Form\DepartmentType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Department"})
     *
     * @param Request $request the request object
     * @param ProductVariant $productVariant
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function postAction(Request $request, ProductVariant $productVariant): View
    {
        $department = new Department();
        $department->setProductVariant($productVariant);

        $form = $this->createForm(DepartmentType::class, $department);
        $form->remove('productVariant');

        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($department);
            $em->flush();

            return $this->view($department, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ProductVariantBundle\Form\DepartmentType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Department"})
     *
     * @param Request $request the request object
     * @param ProductVariant $productVariant
     * @param Department $department
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, ProductVariant $productVariant, Department $department): View
    {
        if ($productVariant->getId() !== $department->getProductVariant()->getId()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(DepartmentType::class, $department);
        $form->remove('productVariant');

        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($department);
            $em->flush();

            return $this->view($department);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @param ProductVariant $productVariant
     * @param Department $department
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(ProductVariant $productVariant, Department $department): View
    {
        if ($productVariant->getId() !== $department->getProductVariant()->getId()) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($department);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__product__get_variant_departments',
            [
                'productVariant' => $productVariant->getId()->toString(),
            ],
            Response::HTTP_NO_CONTENT
        );
    }
}
