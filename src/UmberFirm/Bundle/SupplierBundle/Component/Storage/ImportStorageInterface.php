<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Component\Storage;

use UmberFirm\Bundle\MediaBundle\Component\Storage\MediaStorageInterface;

/**
 * Interface ImportStorageInterface
 *
 * @package UmberFirm\Bundle\SupplierBundle\Component\Storage
 */
interface ImportStorageInterface extends MediaStorageInterface
{
    /**
     * @param string $filename
     *
     * @return string
     */
    public function getContent(string $filename): string;
}
