<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Order\Producer;

use OldSound\RabbitMqBundle\RabbitMq\Producer;
use UmberFirm\Bundle\PublicBundle\DataObject\PublicOrder;

/**
 * Class OrderProducer
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Order\Producer
 */
class OrderProducer extends Producer implements OrderProducerInterface
{
    /**
     * {@inheritdoc}
     */
    public function sendOrder(PublicOrder $publicOrder): void
    {
        $messageBody = serialize($publicOrder);
        $this->publish($messageBody);
    }
}
