<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\View\View;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductSeo;
use UmberFirm\Bundle\ProductBundle\Form\ProductSeoType;

/**
 * Class ProductSeoController
 *
 * @package UmberFirm\Bundle\ProductBundle\Controller
 *
 * @FOS\RouteResource("seo")
 * @FOS\NamePrefix("umberfirm__product__")
 */
class ProductSeoController extends FOSRestController implements ClassResourceInterface
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
     * @FOS\View(serializerGroups={"Default", "ProductSeo"})
     *
     * @param Product $product
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function cgetAction(Product $product): View
    {
        $repository = $this->getDoctrine()->getRepository(ProductSeo::class);
        $items = $repository->findBy(['product' => $product->getId()->toString()]);

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
     * @FOS\View(serializerGroups={"Default", "ProductSeo"})
     *
     * @param Product $product
     * @param ProductSeo $seo
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Product $product, ProductSeo $seo): View
    {
        if ($seo->getProduct()->getId()->toString() !== $product->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        return $this->view($seo);
    }

    /**
     * Get specified item translations
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ProductSeoTranslation"})
     *
     * @param Product $product
     * @param ProductSeo $seo
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getTranslationsAction(Product $product, ProductSeo $seo): View
    {
        if ($seo->getProduct()->getId()->toString() !== $product->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        return $this->view($seo->getTranslations());
    }

    /**
     * Creating new item from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ProductBundle\Form\ProductSeoType",
     *   statusCodes = {
     *     201 = "Returned when created successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ProductSeo"})
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
        $seo = new ProductSeo();
        $seo->setProduct($product);

        $form = $this->createForm(ProductSeoType::class, $seo);
        $form->remove('product');

        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($seo);
            $em->flush();

            return $this->view($seo, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing item from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ProductBundle\Form\ProductSeoType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ProductSeo"})
     *
     * @param Request $request the request object
     * @param Product $product
     * @param ProductSeo $seo
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Product $product, ProductSeo $seo): View
    {
        if ($seo->getProduct()->getId()->toString() !== $product->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(ProductSeoType::class, $seo);
        $form->remove('product');

        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($seo);
            $em->flush();

            return $this->view($seo);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }
}
