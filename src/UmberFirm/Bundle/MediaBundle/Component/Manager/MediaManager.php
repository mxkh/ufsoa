<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\Component\Manager;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use UmberFirm\Bundle\MediaBundle\Component\Producer\MediaProducerInterface;
use UmberFirm\Bundle\MediaBundle\Component\Storage\MediaStorageInterface;

/**
 * Class MediaManager
 *
 * @package UmberFirm\Bundle\MediaBundle\Component\Handler
 */
class MediaManager implements MediaManagerInterface
{
    /**
     * @var MediaStorageInterface
     */
    private $storage;

    /**
     * @var MediaProducerInterface
     */
    private $producer;

    /**
     * MediaManager constructor.
     *
     * @param MediaStorageInterface $storage
     * @param MediaProducerInterface $producer
     */
    public function __construct(MediaStorageInterface $storage, MediaProducerInterface $producer)
    {
        $this->storage = $storage;
        $this->producer = $producer;
    }

    /**
     * {@inheritdoc}
     */
    public function create(UploadedFile $file): ?string
    {
        return $this->storage->create($file);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $filename): void
    {
        $this->producer->delete($filename);
    }
}
