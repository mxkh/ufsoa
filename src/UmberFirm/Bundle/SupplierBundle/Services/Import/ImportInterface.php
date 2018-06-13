<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Services\Import;

use UmberFirm\Bundle\SupplierBundle\Entity\Import;

/**
 * Interface ImportInterface
 *
 * @package UmberFirm\Bundle\SupplierBundle\Services\Import
 */
interface ImportInterface
{
    /**
     * @return bool
     */
    public function validate(): bool;

    /**
     * @return void
     */
    public function import(): void;

    /**
     * @param Import $import
     *
     * @return void
     */
    public function loadData(Import $import): void;
}
