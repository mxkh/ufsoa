<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\ProductBundle\Entity\Feature;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductFeature;
use UmberFirm\Bundle\ProductBundle\Form\ProductFeatureType;

/**
 * Class ProductFeatureController
 *
 * @package UmberFirm\Bundle\ProductBundle\Controller
 *
 * @FOS\RouteResource("product-feature")
 * @FOS\NamePrefix("umberfirm__product__")
 */
class ProductFeatureController extends FOSRestController implements ClassResourceInterface
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
     * @FOS\View(serializerGroups={"Default", "ProductFeature"})
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param Product $product
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher, Product $product): View
    {
        $offset = $paramFetcher->get('offset');
        $limit = $paramFetcher->get('limit');

        $repository = $this->getDoctrine()->getRepository(ProductFeature::class);
        $items = $repository->findBy(['product' => $product], null, $limit, $offset);

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
     * @FOS\View(serializerGroups={"Default", "ProductFeature"})
     *
     * @param Product $product
     * @param Feature $feature
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Product $product, Feature $feature): View
    {
        $repository = $this->getDoctrine()->getRepository(ProductFeature::class);
        $productFeature = $repository->findOneBy(['product' => $product, 'feature' => $feature]);

        if (null === $productFeature) {
            throw $this->createNotFoundException();
        }

        return $this->view($productFeature);
    }

    /**
     * Creates a new item from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ProductBundle\Form\FeatureValueType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ProductFeature"})
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
        $productFeature = new ProductFeature();
        $productFeature->setProduct($product);

        $form = $this->createForm(ProductFeatureType::class, $productFeature);
        //hacked form with removed product. to allow add feature only.
        $form->remove('product');

        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($productFeature);
            $em->flush();

            return $this->view($productFeature, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing item from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ProductBundle\Form\FeatureValueType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ProductFeature"})
     *
     * @param Request $request the request object
     * @param Product $product
     * @param Feature $feature
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Product $product, Feature $feature): View
    {
        $repository = $this->getDoctrine()->getRepository(ProductFeature::class);
        $productFeature = $repository->findOneBy(['product' => $product, 'feature' => $feature]);

        if (null === $productFeature) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(ProductFeatureType::class, $productFeature);
        //hacked form with removed product and feature. to allow update feature values only.
        $form->remove('product')
            ->remove('feature');

        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($productFeature);
            $em->flush();

            return $this->view($productFeature);
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
     * @param Feature $feature
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Product $product, Feature $feature): View
    {
        $repository = $this->getDoctrine()->getRepository(ProductFeature::class);
        $productFeature = $repository->findOneBy(['product' => $product, 'feature' => $feature]);

        if (null === $productFeature) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($productFeature);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__product__get_product_product-features',
            [
                'product' => $product->getId()->toString(),
            ],
            Response::HTTP_NO_CONTENT
        );
    }
}
