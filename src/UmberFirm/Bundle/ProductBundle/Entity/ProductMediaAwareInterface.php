<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * Interface ProductMediaAwareInterface
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 */
interface ProductMediaAwareInterface
{
    /**
     * @return Collection|ProductMedia[]
     */
    public function getMedias(): Collection;

    /**
     * @param ProductMedia $media
     *
     * @return Product
     */
    public function addMedia(ProductMedia $media): Product;

    /**
     * @param ProductMedia $media
     *
     * @return Product
     */
    public function removeMedia(ProductMedia $media): Product;
}
