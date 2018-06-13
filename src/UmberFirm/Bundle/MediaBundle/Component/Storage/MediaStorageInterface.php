<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\Component\Storage;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Interface MediaStorageInterface
 *
 * @package UmberFirm\Bundle\MediaBundle\Component\Storage
 */
interface MediaStorageInterface
{
    /**
     * @param UploadedFile $file
     *
     * @return null|string
     */
    public function create(UploadedFile $file): ?string;

    /**
     * @param string $filename
     *
     * @return bool
     */
    public function delete(string $filename): bool;
}
