<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Component\Producer;

use UmberFirm\Bundle\SupplierBundle\Entity\Import;

/**
 * Interface ImportProducerInterface
 *
 * @package UmberFirm\Bundle\SupplierBundle\Component\Producer
 */
interface ImportProducerInterface
{
    /**
     * @param Import $import
     *
     * @return void
     */
    public function import(Import $import): void;
}
