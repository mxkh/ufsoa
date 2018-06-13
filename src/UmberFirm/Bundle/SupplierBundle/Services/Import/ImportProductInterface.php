<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Services\Import;

/**
 * Interface ImportProductInterface
 *
 * @package UmberFirm\Bundle\SupplierBundle\Services\Import
 */
interface ImportProductInterface extends ImportInterface
{
    /**
     * @return void
     */
    public function importProducts(): void;

    /**
     * @return void
     */
    public function importVariants(): void;

    /**
     * @return void
     */
    public function importDepartments(): void;

    /**
     * @return void
     */
    public function updateProductVariantStock(): void;

    /**
     * @return void
     */
    public function updateProductStock(): void;

    /**
     * @return void
     */
    public function saveImportLogs(): void;
}
