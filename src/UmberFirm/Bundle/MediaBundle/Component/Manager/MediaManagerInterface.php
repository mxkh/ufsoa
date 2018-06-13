<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\Component\Manager;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Interface MediaManagerInterface
 *
 * @package UmberFirm\Bundle\MediaBundle\Component\Handler
 */
interface MediaManagerInterface
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
     * @return void
     */
    public function delete(string $filename): void;
}
