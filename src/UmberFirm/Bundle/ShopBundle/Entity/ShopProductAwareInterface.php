<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Entity;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\ProductBundle\Entity\Product;

/**
 * Interface ShopProductAwareInterface
 *
 * @package UmberFirm\Bundle\ShopBundle\Entity
 */
interface ShopProductAwareInterface
{
    /**
     * @param Product $product
     *
     * @return ShopProductAwareInterface
     */
    public function addProduct(Product $product);

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection;

    /**
     * @param Product $product
     *
     * @return $this
     */
    public function removeProduct(Product $product);
}
