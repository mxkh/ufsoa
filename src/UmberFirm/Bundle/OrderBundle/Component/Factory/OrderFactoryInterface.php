<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Component\Factory;

use UmberFirm\Bundle\OrderBundle\Entity\Order;
use UmberFirm\Bundle\OrderBundle\Entity\OrderItem;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCartItem;
use UmberFirm\Bundle\PublicBundle\Component\Order\DataTransferObject\FastOrderDTOInterface;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Interface OrderFactoryInterface
 *
 * @package UmberFirm\Bundle\OrderBundle\Component\Factory
 */
interface OrderFactoryInterface
{
    /**
     * @param ShoppingCart $cart
     *
     * @return Order
     */
    public function createFromCart(ShoppingCart $cart): Order;

    /**
     * @param FastOrderDTOInterface $fastOrderDTO
     *
     * @return Order
     */
    public function createFromFastOrderDTO(FastOrderDTOInterface $fastOrderDTO): Order;

    /**
     * @param ShoppingCartItem $cartItem
     *
     * @return OrderItem
     */
    public function createOrderItem(ShoppingCartItem $cartItem): OrderItem;

    /**
     * @param Shop $shop
     *
     * @return string
     */
    public function getUniqueOrderNumber(Shop $shop): string;
}
