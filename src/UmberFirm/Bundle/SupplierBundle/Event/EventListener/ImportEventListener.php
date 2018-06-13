<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Event\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use FOS\ElasticaBundle\Persister\ObjectPersisterInterface;
use FOS\ElasticaBundle\Provider\IndexableInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PropertyAccess\PropertyAccess;
use UmberFirm\Bundle\MediaBundle\Entity\MediaEnum;
use UmberFirm\Bundle\MediaBundle\Entity\MimeType;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductImport;
use UmberFirm\Bundle\SupplierBundle\Component\Manager\ImportManagerInterface;
use UmberFirm\Bundle\SupplierBundle\Entity\Import;
use UmberFirm\Bundle\SupplierBundle\Services\Import\ImportProduct;
use UmberFirm\Component\Elastica\Event\SubscriberTrait;

/**
 * Class ImportEventListener
 *
 * @package UmberFirm\Bundle\SupplierBundle\Event\EventListener
 */
final class ImportEventListener implements ImportEventListenerInterface
{
    use SubscriberTrait;

    /**
     * @var ImportManagerInterface
     */
    private $importManager;

    /**
     * @var Import|null
     */
    private $import;

    /**
     * ImportEventListener constructor.
     *
     * @param ImportManagerInterface $manager
     * @param ObjectPersisterInterface $objectPersister
     * @param IndexableInterface $indexable
     * @param array $config
     */
    public function __construct(
        ImportManagerInterface $manager,
        ObjectPersisterInterface $objectPersister,
        IndexableInterface $indexable,
        array $config = []
    )
    {
        $this->importManager = $manager;
        $this->objectPersister = $objectPersister;
        $this->indexable = $indexable;
        $this->config = $config;
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist(LifecycleEventArgs $event): void
    {
        if (false === ($event->getEntity() instanceof Import)) {
            return;
        }

        /** @var Import $import */
        $import = $event->getEntity();
        //make sure we have uploaded file
        if (false === ($import->getFile() instanceof UploadedFile)) {
            $this->import = $import;

            return;
        }

        $filename = $this->importManager->create($import->getFile());
        //if something went wrong and file was not uploaded throw exception.
        if (null === $filename) {
            //TODO: add logger
            throw new \Exception('Something went wrong. Media file was not saved.');
        }

        $this->setImportData($filename, $import, $event->getEntityManager());
        $this->import = $import;
    }

    /**
     * {@inheritdoc}
     */
    public function postFlush(PostFlushEventArgs $args): void
    {
        if (false === ($this->import instanceof Import)) {
            return;
        }

        if (true === (Import::STATUS_CREATED === $this->import->getStatus())) {
            $this->importManager->import($this->import);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function postUpdate(LifecycleEventArgs $event): void
    {
        if (false === ($event->getEntity() instanceof Import)) {
            return;
        }

        /** @var Import $import */
        $import = $event->getEntity();
        if (true === (Import::STATUS_SUCCESS === $import->getStatus())) {
            $this->syncImportedToElastica($import, $event->getEntityManager());
        }
    }

    /**
     * @param string $filename
     * @param Import $import
     * @param EntityManagerInterface $entityManager
     */
    private function setImportData(string $filename, Import $import, EntityManagerInterface $entityManager): void
    {
        $import->setFilename($filename);
        $import->setExtension($import->getFile()->getClientOriginalExtension());
        $import->setMimeType($import->getFile()->getMimeType());
        if (Import::STATUS_INACTIVE === $import->getStatus()) {
            $import->setStatus(Import::STATUS_CREATED);
        }
        $mediaEnum = $this->findMediaEnum($import->getFile()->getMimeType(), $entityManager);
        $import->setMediaEnum($mediaEnum);
        $import->setFile(null);
    }

    /**
     * @param string $mimeType
     * @param EntityManagerInterface $entityManager
     *
     * @return null|MediaEnum
     */
    private function findMediaEnum(string $mimeType, EntityManagerInterface $entityManager): ?MediaEnum
    {
        $mimeTypeObject = $entityManager->getRepository(MimeType::class)->findOneByTemplate($mimeType);
        if (null !== $mimeTypeObject) {
            return $mimeTypeObject->getMediaEnum();
        }

        return null;
    }

    /**
     * @param Import $import
     * @param EntityManagerInterface $em
     */
    private function syncImportedToElastica(Import $import,  EntityManagerInterface $em): void
    {
        $productImportRepository = $em->getRepository(ProductImport::class);
        $iterable = $productImportRepository->productIteratorBySupplier($import->getSupplier(), $import->getShop());

        $i = 1;
        /**
         * @var array|ProductImport[] $row
         */
        foreach ($iterable as $row) {
            /** @var Product $product */
            $product = $row['0']->getProduct();
            $this->replaceOne($product);
            if (($i % ImportProduct::BATCH_SIZE) === 0) {
                $em->clear();
            }
            $i++;
        }
    }
}
