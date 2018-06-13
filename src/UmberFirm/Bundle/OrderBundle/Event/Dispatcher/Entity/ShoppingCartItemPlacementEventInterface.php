<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Event\Dispatcher\Entity;

use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCartItem;

/**
 * Interface ShoppingCartItemPlacementEventInterface
 *
 * @package UmberFirm\Bundle\OrderBundle\Event\Dispatcher\Entity
 */
interface ShoppingCartItemPlacementEventInterface
{
    const NAME = "shopping.cart.item.placement";

    /**
     * @param ShoppingCartItem $shoppingCartItem
     *
     * @return ShoppingCartItemPlacementEventInterface
     */
    public function setShoppingCartItem(ShoppingCartItem $shoppingCartItem): ShoppingCartItemPlacementEventInterface;

    /**
     * @return ShoppingCartItem
     */
    public function getShoppingCartItem(): ShoppingCartItem;
}
