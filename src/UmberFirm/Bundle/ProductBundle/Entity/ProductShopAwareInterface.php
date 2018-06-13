<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Interface ProductShopAwareInterface
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 */
interface ProductShopAwareInterface
{
    /**
     * Get list of shops
     *
     * @return Collection|Shop[]
     */
    public function getShop(): Collection;

    /**
     * Add shop
     *
     * @param Shop $shop
     *
     * @return Product
     */
    public function addShop(Shop $shop): Product;

    /**
     * @param Shop $shop
     *
     * @return Product
     */
    public function removeShop(Shop $shop): Product;
}
