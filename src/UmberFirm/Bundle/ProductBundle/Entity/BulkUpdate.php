<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class BulkUpdate
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 */
class BulkUpdate
{
    /**
     * @var UploadedFile
     */
    protected $file;

    /**
     * @param UploadedFile|null $file
     * 
     * @return BulkUpdate
     */
    public function setFile(?UploadedFile $file): BulkUpdate
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return UploadedFile|null
     */
    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }
}
