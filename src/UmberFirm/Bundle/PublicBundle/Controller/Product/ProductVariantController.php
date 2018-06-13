<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Product;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class ProductVariantController
 *
 * @package UmberFirm\Bundle\ProductBundle\Controller
 *
 * @FOS\RouteResource("variant")
 * @FOS\NamePrefix("umberfirm__public__")
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
     * @FOS\View(serializerGroups={"Default", "ProductVariant"})
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
        /** @var Shop $shop */
        $shop = $this->getUser();

        if ($product->getShop()->getId()->toString() !== $shop->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $offset = $paramFetcher->get('offset');
        $limit = $paramFetcher->get('limit');

        $repository = $this->getDoctrine()->getRepository(ProductVariant::class);
        $items = $repository->findBy(['product' => $product], null, $limit, $offset);

        return $this->view($items);
    }
}

