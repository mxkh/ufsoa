<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Controller;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints\NotNull;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductMedia;
use UmberFirm\Bundle\ProductBundle\Form\ProductMediaType;

/**
 * Class ProductMediaController
 *
 * @package UmberFirm\Bundle\ProductBundle\Controller
 *
 * @FOS\RouteResource("media")
 * @FOS\NamePrefix("umberfirm__product__")
 */
class ProductMediaController extends FOSRestController implements ClassResourceInterface
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
     * @FOS\View(serializerGroups={"Default", "ProductMedia"})
     *
     * @param Product $product
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function cgetAction(Product $product): View
    {
        $repository = $this->getDoctrine()->getRepository(ProductMedia::class);
        $items = $repository->findBy(['product' => $product]);

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
     * @FOS\View(serializerGroups={"Default", "ProductMediaTranslations"})
     *
     * @param Product $product
     * @param ProductMedia $productMedia
     *
     * @return View
     */
    public function getTranslationsAction(Product $product, ProductMedia $productMedia): View
    {
        if ($product->getId()->toString() !== $productMedia->getProduct()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        return $this->view($productMedia->getTranslations());
    }

    /**
     * Creates a new Product Media from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ProductBundle\Form\ProductMediaType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ProductMedia"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $productMedia = new ProductMedia();
        $form = $this->createForm(ProductMediaType::class, $productMedia);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($productMedia);
            $em->flush();

            return $this->view($productMedia, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Updates Product Media from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ProductBundle\Form\ProductMediaType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ProductMedia"})
     *
     * @param Request $request the request object
     * @param Product $product
     * @param ProductMedia $productMedia
     *
     * @return View
     */
    public function putAction(Request $request, Product $product, ProductMedia $productMedia): View
    {
        if ($product->getId()->toString() !== $productMedia->getProduct()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createFormBuilder($productMedia)
            ->add('translations', TranslationsType::class)
            ->getForm();

        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($productMedia);
            $em->flush();

            return $this->view($productMedia);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Updates Product Media position.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ProductMedia"})
     *
     * @param Request $request the request object
     * @param Product $product
     * @param ProductMedia $productMedia
     *
     * @return View
     */
    public function putPositionAction(Request $request, Product $product, ProductMedia $productMedia): View
    {
        if ($product->getId()->toString() !== $productMedia->getProduct()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createFormBuilder($productMedia)
            ->add('position', null, ['constraints' => [new NotNull()]])
            ->getForm();

        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($productMedia);
            $em->flush();

            return $this->view($productMedia);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Removes a ProductMedia.
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
     * @param ProductMedia $productMedia
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Product $product, ProductMedia $productMedia): View
    {
        if ($product->getId()->toString() !== $productMedia->getProduct()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($productMedia);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__product__cget_product_media',
            ['product' => $product->getId()->toString()],
            Response::HTTP_NO_CONTENT
        );
    }
}
