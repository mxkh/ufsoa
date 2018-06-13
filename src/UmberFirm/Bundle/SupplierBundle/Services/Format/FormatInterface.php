<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Services\Format;

/**
 * Interface FormatInterface
 *
 * @package UmberFirm\Bundle\SupplierBundle\Services\Parser\Format
 */
interface FormatInterface
{
    /**
     * @return array
     */
    public function supplierFormat(): array;

    /**
     * @return bool
     */
    public function isValid(): bool;

    /**
     * @return \Generator
     */
    public function buildProductStructure(): \Generator;

    /**
     * @return \Generator
     */
    public function buildVariantStructure(): \Generator;

    /**
     * @return \Generator
     */
    public function buildDepartmentStructure(): \Generator;

    /**
     * @return int
     */
    public function getCountProducts(): int;

    /**
     * @return int
     */
    public function getCountDepartments(): int;

    /**
     * @return int
     */
    public function getCountVariants(): int;
}
