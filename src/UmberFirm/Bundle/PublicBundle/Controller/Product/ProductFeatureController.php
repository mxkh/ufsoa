<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Product;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductFeature;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class ProductFeatureController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Product
 *
 * @FOS\RouteResource("feature")
 * @FOS\NamePrefix("umberfirm__public__")
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
     * @FOS\View(serializerGroups={"PublicService", "PublicProductFeature"})
     *
     * @param Product $product
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function cgetAction(Product $product): View
    {
        /** @var Shop $shop */
        $shop = $this->getUser();

        if ($product->getShop()->getId()->toString() !== $shop->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $repository = $this->getDoctrine()->getRepository(ProductFeature::class);
        $items = $repository->findBy(['product' => $product]);

        return $this->view($items);
    }
}
