<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

/**
 * Interface BulkUpdateCsvDataInterface
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 */
Interface BulkUpdateCsvDataInterface
{
    /**
     * @return string|null
     */
    public function getCode(): ?string;

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * @return string|null
     */
    public function getShortDescription(): ?string;
}
