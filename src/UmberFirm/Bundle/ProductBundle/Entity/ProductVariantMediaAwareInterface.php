<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * Interface ProductVariantMediaAwareInterface
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 */
interface ProductVariantMediaAwareInterface
{
    /**
     * @return Collection|ProductVariantMedia[]
     */
    public function getMedias(): Collection;

    /**
     * @param ProductVariantMedia $media
     *
     * @return ProductVariant
     */
    public function addMedia(ProductVariantMedia $media): ProductVariant;

    /**
     * @param ProductVariantMedia $media
     *
     * @return ProductVariant
     */
    public function removeMedia(ProductVariantMedia $media): ProductVariant;
}
