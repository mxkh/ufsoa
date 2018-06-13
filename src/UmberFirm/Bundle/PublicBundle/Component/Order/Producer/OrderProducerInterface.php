<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Order\Producer;

use UmberFirm\Bundle\PublicBundle\DataObject\PublicOrder;

/**
 * Interface OrderProducerInterface
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Order\Producer
 */
interface OrderProducerInterface
{
    /**
     * @param PublicOrder $publicOrder
     */
    public function sendOrder(PublicOrder $publicOrder): void;
}
