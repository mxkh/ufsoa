<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Controller;

use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierAttributeMapping;
use UmberFirm\Bundle\SupplierBundle\Form\SupplierAttributeMappingType;

/**
 * Class AttributeMappingController
 *
 * @package UmberFirm\Bundle\SupplierBundle\Controller
 *
 * @FOS\RouteResource("attribute-mapping")
 * @FOS\NamePrefix("umberfirm__supplier__")
 */
class AttributeMappingController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of Supplier Attribute Mapping.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
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
     * @FOS\View(serializerGroups={"Default", "SupplierAttributeMapping"})
     *
     * @param Supplier $supplier
     * @param ParamFetcherInterface $paramFetcher
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher, Supplier $supplier): View
    {
        $limit = (int) $paramFetcher->get('limit');
        $searchQuery = $paramFetcher->get('q');
        $currentPage = (int) ($paramFetcher->get('page') ?? 1);

        $pagenator = $this->get('umberfirm.component.pagenator_factory');
        $pagenator->searchByQuery(SupplierAttributeMapping::class, $searchQuery)
            ->getQueryBuilder()
            ->andWhere('supplier_attribute_mapping.supplier = :supplier')
            ->setParameter('supplier', $supplier);

        $representation = $pagenator->getRepresentation(
            $limit,
            $currentPage,
            [
                'supplier' => $supplier->getId()->toString(),
            ]
        );

        return $this->view($representation);
    }

    /**
     * Get specified Supplier Attribute Mapping.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @param Supplier $supplier
     * @param SupplierAttributeMapping $mapping
     *
     * @FOS\View(serializerGroups={"Default", "SupplierAttributeMapping"})
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Supplier $supplier, SupplierAttributeMapping $mapping): View
    {
        if ($supplier->getId()->toString() !== $mapping->getSupplier()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        return $this->view($mapping);
    }

    /**
     * Creates a new item from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\SupplierBundle\Form\SupplierAttributeMappingType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @param Request $request
     * @param Supplier $supplier
     *
     * @FOS\View(serializerGroups={"Default", "SupplierAttributeMapping"})
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function postAction(Request $request, Supplier $supplier): View
    {
        $mapping = new SupplierAttributeMapping();
        $mapping->setSupplier($supplier);
        $form = $this->createForm(SupplierAttributeMappingType::class, $mapping);
        $form->remove('supplier');
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($mapping);
            $em->flush();

            return $this->view($mapping, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing item from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\SupplierBundle\Form\SupplierAttributeMappingType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "SupplierAttributeMapping"})
     *
     * @param Request $request
     * @param Supplier $supplier
     * @param SupplierAttributeMapping $mapping
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Supplier $supplier, SupplierAttributeMapping $mapping): View
    {
        if ($supplier->getId()->toString() !== $mapping->getSupplier()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(SupplierAttributeMappingType::class, $mapping);
        $form->remove('supplier');
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($mapping);
            $em->flush();

            return $this->view($mapping);
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
     * @param Supplier $supplier
     * @param SupplierAttributeMapping $mapping
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Supplier $supplier, SupplierAttributeMapping $mapping): View
    {
        if ($supplier->getId()->toString() !== $mapping->getSupplier()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($mapping);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__supplier__get_supplier_attribute-mappings',
            [
                'supplier' => $supplier->getId()->toString(),
            ],
            Response::HTTP_NO_CONTENT
        );
    }
}
