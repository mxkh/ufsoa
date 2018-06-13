<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Component\Manager;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use UmberFirm\Bundle\SupplierBundle\Component\Producer\ImportProducerInterface;
use UmberFirm\Bundle\SupplierBundle\Component\Storage\ImportStorageInterface;
use UmberFirm\Bundle\SupplierBundle\Entity\Import;

/**
 * Class ImportManager
 *
 * @package UmberFirm\Bundle\SupplierBundle\Component\Manager
 */
class ImportManager implements ImportManagerInterface
{
    /**
     * @var ImportStorageInterface
     */
    private $storage;

    /**
     * @var ImportProducerInterface
     */
    private $producer;

    /**
     * MediaManager constructor.
     *
     * @param ImportStorageInterface $storage
     * @param ImportProducerInterface $producer
     */
    public function __construct(ImportStorageInterface $storage, ImportProducerInterface $producer)
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
        $this->storage->delete($filename);
    }

    /**
     * {@inheritdoc}
     */
    public function import(Import $import): void
    {
        $this->producer->import($import);
    }
}
