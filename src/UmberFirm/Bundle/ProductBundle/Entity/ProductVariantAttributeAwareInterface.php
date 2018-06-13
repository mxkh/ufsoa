<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * Interface ProductVariantAttributeAwareInterface
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 */
interface ProductVariantAttributeAwareInterface
{
    /**
     * @return Collection|Attribute[]
     */
    public function getProductVariantAttributes(): Collection;

    /**
     * @param Attribute $productVariantAttribute
     *
     * @return ProductVariant
     */
    public function addProductVariantAttribute(Attribute $productVariantAttribute): ProductVariant;

    /**
     * @param Attribute $productVariantAttribute
     *
     * @return ProductVariant
     */
    public function removeProductVariantAttribute(Attribute $productVariantAttribute): ProductVariant;
}
