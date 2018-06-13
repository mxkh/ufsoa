<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariantMedia;
use UmberFirm\Bundle\ProductBundle\Form\ProductVariantMediaType;

/**
 * Class ProductVariantMediaController
 *
 * @package UmberFirm\Bundle\ProductBundle\Controller
 *
 * @FOS\RouteResource("variant_media")
 * @FOS\NamePrefix("umberfirm__product__")
 */
class ProductVariantMediaController extends FOSRestController implements ClassResourceInterface
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
     * @FOS\View(serializerGroups={"Default", "ProductVariantMedia"})
     *
     * @param Product $product
     * @param ProductVariant $productVariant
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function cgetAction(
        Product $product,
        ProductVariant $productVariant
    ): View
    {
        if ($product->getId()->toString() !== $productVariant->getProduct()->getId()->toString()) {
            throw $this->createNotFoundException();
        }
        $repository = $this->getDoctrine()->getRepository(ProductVariantMedia::class);
        $items = $repository->findBy(['productVariant' => $productVariant]);

        return $this->view($items);
    }

    /**
     * Creates a new Product Variant Media from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ProductBundle\Form\ProductVariantMediaType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ProductVariantMedia"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $productVariantMedia = new ProductVariantMedia();
        $form = $this->createForm(ProductVariantMediaType::class, $productVariantMedia);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($productVariantMedia);
            $em->flush();

            return $this->view($productVariantMedia, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Removes a Product Variant Media.
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
     * @param ProductVariantMedia $productVariantMedia
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(
        Product $product,
        ProductVariant $productVariant,
        ProductVariantMedia $productVariantMedia
    ): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($productVariantMedia);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__product__cget_product_variant_media',
            [
                'product' => $product->getId()->toString(),
                'productVariant' => $productVariant->getId()->toString(),
            ],
            Response::HTTP_NO_CONTENT
        );
    }
}
