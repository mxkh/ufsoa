<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * Interface ProductFeatureAwareInterface
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 */
interface ProductFeatureAwareInterface
{
    /**
     * @return Collection|ProductFeature[]
     */
    public function getProductFeatures(): Collection;

    /**
     * @param ProductFeature $productFeature
     *
     * @return Product
     */
    public function addProductFeature(ProductFeature $productFeature): Product;

    /**
     * @param ProductFeature $productFeature
     *
     * @return Product
     */
    public function removeProductFeature(ProductFeature $productFeature): Product;
}
