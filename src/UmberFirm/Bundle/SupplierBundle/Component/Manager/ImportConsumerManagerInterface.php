<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Component\Manager;

/**
 * Interface ImportConsumerManagerInterface
 *
 * @package UmberFirm\Bundle\SupplierBundle\Component\Manager
 */
interface ImportConsumerManagerInterface
{
    /**
     * @param string $importId
     *
     * @return bool
     */
    public function manage(string $importId): bool;
}
