<?php

namespace UmberFirm\Bundle\OrderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Interface ShoppingCartAwareInterface
 *
 * @package UmberFirm\Bundle\OrderBundle\Entity
 */
interface ShoppingCartItemAwareInterface
{
    /**
     * @return ArrayCollection|ShoppingCartItem[]
     */
    public function getShoppingCartItems();

    /**
     * @param ShoppingCartItem $shoppingCartItem
     *
     * @return $this
     */
    public function addShoppingCartItem(ShoppingCartItem $shoppingCartItem);

    /**
     * @param ShoppingCartItem $shoppingCartItem
     *
     * @return $this
     */
    public function removeShoppingCartItem(ShoppingCartItem $shoppingCartItem);
}
