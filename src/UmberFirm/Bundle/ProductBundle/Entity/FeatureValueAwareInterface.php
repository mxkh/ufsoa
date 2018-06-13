<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * Interface FeatureValueAwareInterface
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 */
interface FeatureValueAwareInterface
{
    /**
     * @return Collection|FeatureValue[]
     */
    public function getFeatureValues(): Collection;

    /**
     * @param FeatureValue $featureValue
     *
     * @return Feature
     */
    public function addFeatureValue(FeatureValue $featureValue): Feature;

    /**
     * @param FeatureValue $featureValue
     *
     * @return Feature
     */
    public function removeFeatureValue(FeatureValue $featureValue): Feature;
}
