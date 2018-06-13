<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Order\Producer;

use OldSound\RabbitMqBundle\RabbitMq\Producer;
use UmberFirm\Bundle\PublicBundle\Component\Order\DataTransferObject\FastOrderDTOInterface;

/**
 * Class FastOrderProducer
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Order\Producer
 */
class FastOrderProducer extends Producer implements FastOrderProducerInterface
{
    /**
     * {@inheritdoc}
     */
    public function save(FastOrderDTOInterface $fastOrderDTO): void
    {
        $this->publish(serialize($fastOrderDTO));
    }
}
