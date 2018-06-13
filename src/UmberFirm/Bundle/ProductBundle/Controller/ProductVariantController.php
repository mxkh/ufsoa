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
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\ProductBundle\Form\ProductVariantType;

/**
 * Class ProductVariantController
 *
 * @package UmberFirm\Bundle\ProductBundle\Controller
 *
 * @FOS\RouteResource("variant")
 * @FOS\NamePrefix("umberfirm__product__")
 */
class ProductVariantController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of items
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ProductVariant"})
     *
     * @param Product $product
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function cgetAction(Product $product): View
    {
        $repository = $this->getDoctrine()->getRepository(ProductVariant::class);
        $items = $repository->findBy(['product' => $product]);

        return $this->view($items);
    }

    /**
     * Get specified item
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ProductVariant"})
     *
     * @param Product $product
     * @param ProductVariant $productVariant
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Product $product, ProductVariant $productVariant): View
    {
        if ($product->getId() !== $productVariant->getProduct()->getId()) {
            throw $this->createNotFoundException();
        }

        return $this->view($productVariant);
    }

    /**
     * Creates a new item from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ProductBundle\Form\ProductVariantType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ProductVariant"})
     *
     * @param Request $request the request object
     * @param Product $product
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function postAction(Request $request, Product $product): View
    {
        $productVariant = new ProductVariant();
        $productVariant->setProduct($product);

        $form = $this->createForm(ProductVariantType::class, $productVariant);
        //hacked form with removed product. to allow add variant only.
        $form->remove('product');

        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($productVariant);
            $em->flush();

            return $this->view($productVariant, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing item from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ProductBundle\Form\ProductVariantType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ProductVariant"})
     *
     * @param Request $request the request object
     * @param Product $product
     * @param ProductVariant $productVariant
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Product $product, ProductVariant $productVariant): View
    {
        if ($product->getId() !== $productVariant->getProduct()->getId()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(ProductVariantType::class, $productVariant);
        //hacked form with removed product. to allow update product variant only.
        $form->remove('product');

        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($productVariant);
            $em->flush();

            return $this->view($productVariant);
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
     * @param Product $product
     * @param ProductVariant $productVariant
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Product $product, ProductVariant $productVariant): View
    {
        if ($product->getId() !== $productVariant->getProduct()->getId()) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($productVariant);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__product__get_product_variants',
            [
                'product' => $product->getId()->toString(),
            ],
            Response::HTTP_NO_CONTENT
        );
    }
}
