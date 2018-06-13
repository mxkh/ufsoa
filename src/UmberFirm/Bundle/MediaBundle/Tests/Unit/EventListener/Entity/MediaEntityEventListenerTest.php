<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\Tests\Unit\EventListener\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use UmberFirm\Bundle\MediaBundle\Component\Manager\MediaManagerInterface;
use UmberFirm\Bundle\MediaBundle\Entity\Media;
use UmberFirm\Bundle\MediaBundle\Entity\MediaEnum;
use UmberFirm\Bundle\MediaBundle\Entity\MimeType;
use UmberFirm\Bundle\MediaBundle\EventListener\Entity\MediaEntityEventListener;
use UmberFirm\Bundle\MediaBundle\Repository\MimeTypeRepository;

/**
 * Class MediaEntityEventListenerTest
 *
 * @package UmberFirm\Bundle\MediaBundle\Tests\Unit\EventListener\Entity
 */
class MediaEntityEventListenerTest extends \PHPUnit_Framework_TestCase
{
    /** @var MediaManagerInterface */
    private $manager;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var LifecycleEventArgs */
    private $lifecycleEventArgs;

    /** @var MimeTypeRepository */
    private $repository;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->manager = $this->getMockBuilder(MediaManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->lifecycleEventArgs = $this->getMockBuilder(LifecycleEventArgs::class)
            ->disableOriginalConstructor()
            ->setMethods(['getEntityManager', 'getEntity'])
            ->getMock();

        $this->lifecycleEventArgs
            ->method('getEntityManager')
            ->willReturn($this->entityManager);

        $this->repository = $this->getMockBuilder(MimeTypeRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findOneByTemplate'])
            ->getMock();

        $this->entityManager
            ->method('getRepository')
            ->willReturn($this->repository);
    }

    public function testPrePersistWrongEntity()
    {
        $media = new \stdClass();

        $this->lifecycleEventArgs
            ->expects($this->once())
            ->method('getEntity')
            ->willReturn($media);
        $this->manager
            ->expects($this->never())
            ->method('create');

        $eventListener = new MediaEntityEventListener($this->manager);
        $eventListener->prePersist($this->lifecycleEventArgs);
    }

    public function testPrePersistWithoutFile()
    {
        $media = new Media();

        $this->lifecycleEventArgs
            ->expects($this->atLeastOnce())
            ->method('getEntity')
            ->willReturn($media);

        $this->manager
            ->expects($this->never())
            ->method('create');

        $eventListener = new MediaEntityEventListener($this->manager);
        $eventListener->prePersist($this->lifecycleEventArgs);
    }

    public function testPrePersistWithMimeType()
    {
        $media = new Media();
        $media->setFile(new UploadedFile(__DIR__.'/../../../Functional/Fixtures/1.jpg', '1.jpg'));

        $mediaEnum = (new MediaEnum())
            ->setName('image');

        $mimeType = (new MimeType())
            ->setMediaEnum($mediaEnum)
            ->setName('jpeg')
            ->setTemplate('image/jpeg');

        $this->lifecycleEventArgs
            ->expects($this->atLeastOnce())
            ->method('getEntity')
            ->willReturn($media);

        $this->manager
            ->expects($this->once())
            ->method('create')
            ->willReturn('filename.jpg');

        $this->repository
            ->expects($this->once())
            ->method('findOneByTemplate')
            ->willReturn($mimeType);

        $eventListener = new MediaEntityEventListener($this->manager);
        $eventListener->prePersist($this->lifecycleEventArgs);

        $this->assertEquals('filename.jpg', $this->lifecycleEventArgs->getEntity()->getFilename());
        $this->assertEquals($mediaEnum, $this->lifecycleEventArgs->getEntity()->getMediaEnum());
        $this->assertEquals('image/jpeg', $this->lifecycleEventArgs->getEntity()->getMimeType());
    }

    public function testPrePersistWithoutMimeType()
    {
        $media = new Media();
        $media->setFile(new UploadedFile(__DIR__.'/../../../Functional/Fixtures/1.jpg', '1.jpg'));

        $this->lifecycleEventArgs
            ->expects($this->atLeastOnce())
            ->method('getEntity')
            ->willReturn($media);

        $this->manager
            ->expects($this->once())
            ->method('create')
            ->willReturn('filename.jpg');

        $this->repository
            ->expects($this->once())
            ->method('findOneByTemplate')
            ->willReturn(null);

        $eventListener = new MediaEntityEventListener($this->manager);
        $eventListener->prePersist($this->lifecycleEventArgs);

        $this->assertEquals('filename.jpg', $this->lifecycleEventArgs->getEntity()->getFilename());
        $this->assertEquals(null, $this->lifecycleEventArgs->getEntity()->getMediaEnum());
        $this->assertEquals('image/jpeg', $this->lifecycleEventArgs->getEntity()->getMimeType());
    }

    /**
     * @expectedException \Exception
     */
    public function testPrePersistFilenameFalse()
    {
        $media = new Media();
        $media->setFile(new UploadedFile(__DIR__.'/../../../Functional/Fixtures/1.jpg', '1.jpg'));

        $this->lifecycleEventArgs
            ->expects($this->atLeastOnce())
            ->method('getEntity')
            ->willReturn($media);

        $this->manager
            ->expects($this->once())
            ->method('create')
            ->willReturn(null);

        $this->repository
            ->expects($this->never())
            ->method('findOneByTemplate');

        $eventListener = new MediaEntityEventListener($this->manager);
        $eventListener->prePersist($this->lifecycleEventArgs);
    }

    public function testPreRemove()
    {
        $media = (new Media())
            ->setFilename('filename.jpg');

        $this->lifecycleEventArgs
            ->expects($this->atLeastOnce())
            ->method('getEntity')
            ->willReturn($media);

        $this->manager
            ->expects($this->once())
            ->method('delete');

        $eventListener = new MediaEntityEventListener($this->manager);
        $eventListener->preRemove($this->lifecycleEventArgs);
    }

    public function testPreRemoveWrongEntity()
    {
        $media = new \stdClass();

        $this->lifecycleEventArgs
            ->expects($this->once())
            ->method('getEntity')
            ->willReturn($media);
        $this->manager
            ->expects($this->never())
            ->method('delete');

        $eventListener = new MediaEntityEventListener($this->manager);
        $eventListener->preRemove($this->lifecycleEventArgs);
    }
}
