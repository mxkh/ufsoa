<?php

declare(strict_types=1);

namespace UmberFirm\Component\Validator\Constraints\ShoppingCart;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Interface ShoppingCartInterface
 *
 * @package UmberFirm\Component\Validator\Constraints\ShoppingCart
 */
interface ShoppingCartInterface
{
    /**
     * @return null|Customer
     */
    public function getCustomer(): ?Customer;

    /**
     * @return null|Shop
     */
    public function getShop(): ?Shop;

    /**
     * @return null|ShoppingCart
     */
    public function getShoppingCart(): ?ShoppingCart;
}
