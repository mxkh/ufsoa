<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Event\Order;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\FastOrder;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Interface FastOrderEventInterface
 *
 * @package UmberFirm\Bundle\PublicBundle\Event\Order
 */
interface FastOrderEventInterface
{
    const PLACEMENT = 'fast_order.placement';

    /**
     * @return FastOrder
     */
    public function getFastOrder(): FastOrder;

    /**
     * @return Shop
     */
    public function getShop(): Shop;

    /**
     * @return null|Customer
     */
    public function getCustomer(): ?Customer;
}
