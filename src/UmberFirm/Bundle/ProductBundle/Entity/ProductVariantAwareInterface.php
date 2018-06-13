<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * Interface ProductVariantAwareInterface
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 */
interface ProductVariantAwareInterface
{
    /**
     * @return Collection|ProductVariant[]
     */
    public function getProductVariants(): Collection;

    /**
     * @param ProductVariant $productVariant
     *
     * @return Product
     */
    public function addProductVariant(ProductVariant $productVariant): Product;

    /**
     * @param ProductVariant $productVariant
     *
     * @return Product
     */
    public function removeProductVariant(ProductVariant $productVariant): Product;
}
