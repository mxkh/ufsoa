<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\Component\Producer;

use OldSound\RabbitMqBundle\RabbitMq\Producer;

/**
 * Class MediaProducer
 *
 * @package UmberFirm\Bundle\MediaBundle\Component\Producer
 */
class MediaProducer extends Producer implements MediaProducerInterface
{
    /**
     * {@inheritdoc}
     */
    public function delete(string $filename): void
    {
        $this->publish(json_encode(['filename' => $filename]));
    }
}
