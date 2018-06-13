<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Order\Producer;

use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use UmberFirm\Bundle\PublicBundle\Component\Order\DataTransferObject\FastOrderDTOInterface;

/**
 * Interface FastOrderProducerInterface
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Order\Producer
 */
interface FastOrderProducerInterface extends ProducerInterface
{
    /**
     * @param FastOrderDTOInterface $fastOrderDTO
     */
    public function save(FastOrderDTOInterface $fastOrderDTO): void;
}
