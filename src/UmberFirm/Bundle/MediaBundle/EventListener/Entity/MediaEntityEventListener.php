<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\EventListener\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use UmberFirm\Bundle\MediaBundle\Component\Manager\MediaManagerInterface;
use UmberFirm\Bundle\MediaBundle\Entity\Media;
use UmberFirm\Bundle\MediaBundle\Entity\MediaEnum;
use UmberFirm\Bundle\MediaBundle\Entity\MimeType;

/**
 * Class MediaEntityEventListener
 *
 * @package UmberFirm\Bundle\MediaBundle\EventListener\Entity
 */
class MediaEntityEventListener implements MediaEntityEventListenerInterface
{
    /**
     * @var MediaManagerInterface
     */
    private $manager;

    /**
     * MediaEntityEventListener constructor.
     *
     * @param MediaManagerInterface $manager
     */
    public function __construct(MediaManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist(LifecycleEventArgs $event): void
    {
        if (false === ($event->getEntity() instanceof Media)) {
            return;
        }

        /** @var Media $media */
        $media = $event->getEntity();
        //make sure we have uploaded file
        if (false === ($media->getFile() instanceof UploadedFile)) {
            return;
        }

        $filename = $this->manager->create($media->getFile());
        //if something went wrong and file was not uploaded throw exception.
        if (null === $filename) {
            //TODO: add logger
            throw new \Exception('Something went wrong. Media file was not saved.');
        }

        $this->setMediaData($filename, $media, $event->getEntityManager());
    }

    /**
     * {@inheritdoc}
     */
    public function preRemove(LifecycleEventArgs $event): void
    {
        if (false === ($event->getEntity() instanceof Media)) {
            return;
        }

        $this->manager->delete($event->getEntity()->getFilename());
    }

    /**
     * @param string $filename
     * @param Media $media
     * @param EntityManagerInterface $entityManager
     *
     * @return void
     */
    private function setMediaData(string $filename, Media $media, EntityManagerInterface $entityManager): void
    {
        $media->setFilename($filename);
        $media->setExtension($media->getFile()->getClientOriginalExtension());
        $media->setMimeType($media->getFile()->getMimeType());

        $mediaEnum = $this->findMediaEnum($media->getFile()->getMimeType(), $entityManager);
        $media->setMediaEnum($mediaEnum);
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
}
