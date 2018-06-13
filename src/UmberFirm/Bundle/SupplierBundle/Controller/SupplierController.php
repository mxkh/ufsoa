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
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;
use UmberFirm\Bundle\SupplierBundle\Form\SupplierType;

/**
 * @FOS\RouteResource("supplier")
 * @FOS\NamePrefix("umberfirm__supplier__")
 */
class SupplierController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of currencies.
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
     *
     * @FOS\View(serializerGroups={"Default", "Supplier"})
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
        $pagenator->searchByQuery(Supplier::class, $searchQuery);
        $representation = $pagenator->getRepresentation($limit, $currentPage);

        return $this->view($representation);
    }

    /**
     * Get specified supplier.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Supplier"})
     *
     * @param Supplier $supplier
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Supplier $supplier): View
    {
        return $this->view($supplier);
    }

    /**
     * Get Translations of Supplier
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "SupplierTranslation"})
     *
     * @param Supplier $supplier
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getTranslationsAction(Supplier $supplier): View
    {
        return $this->view($supplier->getTranslations());
    }

    /**
     * Creates a new item from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\SupplierBundle\Form\SupplierType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     * @FOS\View(serializerGroups={"Default", "Supplier"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $supplier = new Supplier();
        $form = $this->createForm(SupplierType::class, $supplier);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($supplier);
            $em->flush();

            return $this->view($supplier, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing item from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\SupplierBundle\Form\SupplierType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Supplier"})
     *
     * @param Request $request the request object
     * @param Supplier $supplier
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Supplier $supplier): View
    {
        $form = $this->createForm(SupplierType::class, $supplier);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($supplier);
            $em->flush();

            return $this->view($supplier);
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
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Supplier $supplier): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($supplier);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__supplier__get_suppliers',
            [],
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @FOS\View(serializerGroups={"Default", "Supplier"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function getImportAction(Request $request): View
    {
        $username = trim($request->get('username'));
        $password = trim($request->get('password'));
        $supplierRepository = $this->getDoctrine()->getRepository(Supplier::class);
        $supplier = $supplierRepository->findOneBy(['username' => $username, 'password' => $password]);
        if (null !== $supplier) {
            return $this->view($supplier);
        }

        return $this->view(NULL, Response::HTTP_NOT_FOUND);
    }

    /**
     * @FOS\View(serializerGroups={"Default", "Supplier"})
     *
     * @param Request $request the request object
     * @param Shop $shop
     *
     * @return View
     */
    public function postImportAction(Request $request, Shop $shop): View
    {
        $username = $request->get('username');
        $em = $this->getDoctrine()->getManager();

        $supplierRepository = $this->getDoctrine()->getRepository(Supplier::class);
        $supplier = $supplierRepository->findOneBy(['username' => $username,]);
        if (null !== $supplier) {
            $supplier->addShop($shop);
            $em->persist($supplier);
            $em->flush();

            return $this->view($supplier);
        }

        $supplier = new Supplier();
        $supplier->addShop($shop);
        $form = $this->createForm(SupplierType::class, $supplier);
        $form->remove('shops');
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em->persist($supplier);
            $em->flush();

            return $this->view($supplier, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }
}
