<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * Interface ProductFeatureValueAwareInterface
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 */
interface ProductFeatureValueAwareInterface
{
    /**
     * @return Collection|FeatureValue[]
     */
    public function getProductFeatureValues(): Collection;

    /**
     * @param FeatureValue $featureValue
     *
     * @return ProductFeature
     */
    public function addProductFeatureValue(FeatureValue $featureValue): ProductFeature;

    /**
     * @param FeatureValue $featureValue
     *
     * @return ProductFeature
     */
    public function removeProductFeatureValue(FeatureValue $featureValue): ProductFeature;
}
