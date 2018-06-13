<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Product;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Controller\Annotations as  FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class AttributeController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Product
 *
 * @FOS\RouteResource("attribute")
 * @FOS\NamePrefix("umberfirm__public__")
 */
class AttributeController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of attributes.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicProductAttribute"})
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

        /** @var ArrayCollection $productVariants */
        $productVariants = $product->getProductVariants();
        $productAttributes = new ArrayCollection();

        /** @var ProductVariant $productVariant */
        foreach ($productVariants as $productVariant) {
            foreach ($productVariant->getProductVariantAttributes() as $productAttribute) {
                if (false === $productAttributes->contains($productAttribute)) {
                    $productAttributes->add($productAttribute);
                }
            }
        }

        return $this->view($productAttributes);
    }
}
