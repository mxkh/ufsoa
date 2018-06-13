<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Event\Order\Factory;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\FastOrder;
use UmberFirm\Bundle\PublicBundle\Event\Order\FastOrderEvent;
use UmberFirm\Bundle\PublicBundle\Event\Order\FastOrderEventInterface;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class FastOrderEventFactory
 *
 * @package UmberFirm\Bundle\PublicBundle\Event\Order\Factory
 */
class FastOrderEventFactory implements FastOrderEventFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createFastOrderEvent(FastOrder $fastOrder, Shop $shop, ?Customer $customer): FastOrderEventInterface
    {
        return new FastOrderEvent($fastOrder, $shop, $customer);
    }
}
