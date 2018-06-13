<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\Component\Producer;

use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

/**
 * Interface MediaProducerInterface
 *
 * @package UmberFirm\Bundle\MediaBundle\Component\Producer
 */
interface MediaProducerInterface extends ProducerInterface
{
    /**
     * @param string $filename
     *
     * @return void
     */
    public function delete(string $filename): void;
}
