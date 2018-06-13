<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\Component\Storage;

use Gaufrette\Adapter\AwsS3;
use Gaufrette\Filesystem;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class MediaStorage
 *
 * @package UmberFirm\Bundle\MediaBundle\Component\Storage
 */
class MediaStorage implements MediaStorageInterface
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * MediaStorage constructor.
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

        /** @var AwsS3 $adapter */
        $adapter = $this->filesystem->getAdapter();
        $adapter->setMetadata($filename, ['ContentType' => $file->getMimeType()]);
        $response = $adapter->write($filename, file_get_contents($file->getPathname()));
        if (false !== $response) {
            return $filename;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $filename): bool
    {
        /** @var AwsS3 $adapter */
        $adapter = $this->filesystem->getAdapter();
        $result = $adapter->delete($filename);

        return $result;
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
