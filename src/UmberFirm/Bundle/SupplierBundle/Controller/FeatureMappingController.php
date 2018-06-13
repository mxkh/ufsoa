<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierFeatureMapping;
use UmberFirm\Bundle\SupplierBundle\Form\SupplierFeatureMappingType;

/**
 * Class FeatureMappingController
 *
 * @package UmberFirm\Bundle\SupplierBundle\Controller
 *
 * @FOS\RouteResource("feature-mapping")
 * @FOS\NamePrefix("umberfirm__supplier__")
 */
class FeatureMappingController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of Supplier Feature Mapping.
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
     *     name="offset",
     *     requirements="\d+",
     *     nullable=true,
     *     description="Offset from which to start listing items."
     * )
     * @FOS\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="10",
     *     description="How many items to return."
     * )
     *
     * @param Supplier $supplier
     * @param ParamFetcherInterface $paramFetcher
     *
     * @FOS\View(serializerGroups={"Default", "SupplierFeatureMapping"})
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher, Supplier $supplier): View
    {
        $offset = $paramFetcher->get('offset');
        $limit = $paramFetcher->get('limit');

        $featureMappingRepository = $this->getDoctrine()->getRepository(SupplierFeatureMapping::class);
        $mappings = $featureMappingRepository->findBy(
            [
                'supplier' => $supplier->getId()->toString(),
            ],
            null,
            $limit,
            $offset
        );

        return $this->view($mappings);
    }

    /**
     * Get specified Supplier Feature Mapping.
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
     * @param SupplierFeatureMapping $mapping
     *
     * @FOS\View(serializerGroups={"Default", "SupplierFeatureMapping"})
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Supplier $supplier, SupplierFeatureMapping $mapping): View
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
     *   input = "UmberFirm\Bundle\SupplierBundle\Form\SupplierFeatureMappingType",
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
     * @FOS\View(serializerGroups={"Default", "SupplierFeatureMapping"})
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function postAction(Request $request, Supplier $supplier): View
    {
        $mapping = new SupplierFeatureMapping();
        $mapping->setSupplier($supplier);
        $form = $this->createForm(SupplierFeatureMappingType::class, $mapping);
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
     *   input = "UmberFirm\Bundle\SupplierBundle\Form\SupplierFeatureMappingType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "SupplierFeatureMapping"})
     *
     * @param Request $request
     * @param Supplier $supplier
     * @param SupplierFeatureMapping $mapping
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Supplier $supplier, SupplierFeatureMapping $mapping): View
    {
        if ($supplier->getId()->toString() !== $mapping->getSupplier()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(SupplierFeatureMappingType::class, $mapping);
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
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @param Supplier $supplier
     * @param SupplierFeatureMapping $mapping
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Supplier $supplier, SupplierFeatureMapping $mapping): View
    {
        if ($supplier->getId()->toString() !== $mapping->getSupplier()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($mapping);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__supplier__get_supplier_feature-mappings',
            [
                'supplier' => $supplier->getId()->toString(),
            ],
            Response::HTTP_NO_CONTENT
        );
    }
}
