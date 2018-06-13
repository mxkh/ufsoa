<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Component\Producer;

use OldSound\RabbitMqBundle\RabbitMq\Producer;
use UmberFirm\Bundle\SupplierBundle\Entity\Import;

/**
 * Class ImportProductProducer
 *
 * @package UmberFirm\Bundle\SupplierBundle\Component\Producer
 */
final class ImportProductProducer extends Producer implements ImportProducerInterface
{
    /**
     * {@inheritdoc}
     */
    public function import(Import $import): void
    {
        $this->publish(json_encode(['import' => $import->getId()->toString()]));
    }
}
