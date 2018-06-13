<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Event\Order\Factory;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\FastOrder;
use UmberFirm\Bundle\PublicBundle\Event\Order\FastOrderEventInterface;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Interface FastOrderEventFactoryInterface
 *
 * @package UmberFirm\Bundle\PublicBundle\Event\Order\Factory
 */
interface FastOrderEventFactoryInterface
{
    /**
     * @param FastOrder $fastOrder
     * @param Shop $shop
     * @param null|Customer $customer
     *
     * @return FastOrderEventInterface
     */
    public function createFastOrderEvent(FastOrder $fastOrder, Shop $shop, ?Customer $customer): FastOrderEventInterface;
}
