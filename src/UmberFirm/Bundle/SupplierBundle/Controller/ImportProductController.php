<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\SupplierBundle\Entity\Import;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;

/**
 * Class ImportProductController
 *
 * @package UmberFirm\Bundle\SupplierBundle\Controller
 *
 * @FOS\RouteResource("import-product")
 * @FOS\NamePrefix("umberfirm__supplier__")
 */
class ImportProductController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of imports
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
     * @FOS\View(serializerGroups={"Default", "Import"})
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
        $pagenator->searchByQuery(Import::class, $searchQuery);
        $representation = $pagenator->getRepresentation($limit, $currentPage);

        return $this->view($representation);
    }

    /**
     * Creates a new Import file from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\SupplierBundle\Form\ImportType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Import"})
     *
     * @param Request $request
     * @param Supplier $supplier
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function postAction(Request $request, Supplier $supplier): View
    {
        $import = new Import();
        $import->setSupplier($supplier);

        $data = array_merge($request->files->all(), $request->request->all());
        $form = $this->createFormBuilder($import)
            ->add('file', FileType::class)
            ->add('shop', EntityType::class, ['class' => Shop::class])
            ->add('version')
            ->getForm();

        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($import);
            $em->flush();

            return $this->view($import, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Remove Import item
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @param Import $import
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Import $import): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($import);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__supplier__get_import-products',
            [],
            Response::HTTP_NO_CONTENT
        );
    }
}
