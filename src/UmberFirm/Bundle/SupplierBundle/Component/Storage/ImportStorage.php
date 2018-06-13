<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Component\Storage;

use Gaufrette\Filesystem;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ImportStorage
 *
 * @package UmberFirm\Bundle\SupplierBundle\Component\Storage
 */
class ImportStorage implements ImportStorageInterface
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * ImportStorage constructor.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function create(UploadedFile $file): ?string
    {
        $filename = $this->generateFilename($file);

        $result = $this->filesystem->write($filename, file_get_contents($file->getPathname()));
        if (false !== $result) {
            return $filename;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $filename): bool
    {
        $result = $this->filesystem->delete($filename);

        return $result;
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    public function getContent(string $filename): string
    {
        return $this->filesystem->get($filename)->getContent();
    }

    /**
     * @param UploadedFile $file
     *
     * @return string
     */
    protected function generateFilename(UploadedFile $file): string
    {
        //generate new filename
        $filename = sprintf('%s.%s', md5(Uuid::uuid4()->toString()), $file->getClientOriginalExtension());

        return $filename;
    }
}
