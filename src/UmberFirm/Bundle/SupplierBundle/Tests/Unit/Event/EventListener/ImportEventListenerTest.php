<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Tests\Unit\Event\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use FOS\ElasticaBundle\Persister\ObjectPersisterInterface;
use FOS\ElasticaBundle\Provider\IndexableInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use UmberFirm\Bundle\MediaBundle\Entity\MediaEnum;
use UmberFirm\Bundle\MediaBundle\Entity\MimeType;
use UmberFirm\Bundle\MediaBundle\Repository\MimeTypeRepository;
use UmberFirm\Bundle\SupplierBundle\Component\Manager\ImportManagerInterface;
use UmberFirm\Bundle\SupplierBundle\Entity\Import;
use UmberFirm\Bundle\SupplierBundle\Event\EventListener\ImportEventListener;

/**
 * Class ImportEventListenerTest
 *
 * @package UmberFirm\Bundle\SupplierBundle\Tests\Unit\Event\EventListener
 */
class ImportEventListenerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|ImportManagerInterface */
    private $manager;

    /** @var \PHPUnit_Framework_MockObject_MockObject|LifecycleEventArgs */
    private $lifecycleEventArgs;

    /** @var \PHPUnit_Framework_MockObject_MockObject|PostFlushEventArgs */
    private $postFlushEventArgs;

    /** @var \PHPUnit_Framework_MockObject_MockObject|EntityManagerInterface */
    private $entityManager;

    /** @var \PHPUnit_Framework_MockObject_MockObject|MimeTypeRepository */
    private $repository;

    /** @var \PHPUnit_Framework_MockObject_MockObject|ObjectPersisterInterface */
    private $objectPersister;

    /** @var \PHPUnit_Framework_MockObject_MockObject|IndexableInterface */
    private $indexableInterface;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->manager = $this->createMock(ImportManagerInterface::class);
        $this->lifecycleEventArgs = $this->createMock(LifecycleEventArgs::class);
        $this->postFlushEventArgs = $this->createMock(PostFlushEventArgs::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->repository = $this->createMock(MimeTypeRepository::class);
        $this->objectPersister = $this->createMock(ObjectPersisterInterface::class);
        $this->indexableInterface = $this->createMock(IndexableInterface::class);
    }

    public function testPrePersist()
    {
        $mimeType = new MimeType();
        $mimeType->setMediaEnum(new MediaEnum());

        $this->repository
            ->expects($this->once())
            ->method('findOneByTemplate')
            ->willReturn($mimeType);

        $this->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->repository);

        $this->lifecycleEventArgs
            ->expects($this->once())
            ->method('getEntityManager')
            ->willReturn($this->entityManager);

        $this->manager
            ->expects($this->once())
            ->method('create')
            ->willReturn('filename.xml');

        $importEventListener = new ImportEventListener($this->manager, $this->objectPersister, $this->indexableInterface);

        $import = new Import();
        $import->setFile(
            new UploadedFile(
                __DIR__.'/../../../Fixtures/importExampleFormatV1.xml',
                'importExampleFormatV1.xml'
            )
        );

        $this->lifecycleEventArgs
            ->expects($this->any())
            ->method('getEntity')
            ->willReturn($import);

        $importEventListener->prePersist($this->lifecycleEventArgs);
        $this->assertNull($import->getFile());
        $this->assertEquals('filename.xml', $import->getFilename());
        $this->assertEquals('xml', $import->getExtension());
        $this->assertEquals('application/xml', $import->getMimeType());
        $this->assertInstanceOf(MediaEnum::class, $import->getMediaEnum());
    }

    public function testPrePersistWrongEntity()
    {
        $this->manager
            ->expects($this->never())
            ->method('create');

        $importEventListener = new ImportEventListener($this->manager, $this->objectPersister, $this->indexableInterface);

        $this->lifecycleEventArgs
            ->expects($this->once())
            ->method('getEntity')
            ->willReturn(null);

        $importEventListener->prePersist($this->lifecycleEventArgs);
    }

    public function testPrePersistNonMimeType()
    {
        $this->repository
            ->expects($this->once())
            ->method('findOneByTemplate')
            ->willReturn(null);

        $this->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->repository);

        $this->lifecycleEventArgs
            ->expects($this->once())
            ->method('getEntityManager')
            ->willReturn($this->entityManager);

        $this->manager
            ->expects($this->once())
            ->method('create')
            ->willReturn('filename.xml');

        $importEventListener = new ImportEventListener($this->manager, $this->objectPersister, $this->indexableInterface);

        $import = new Import();
        $import->setFile(
            new UploadedFile(
                __DIR__.'/../../../Fixtures/importExampleFormatV1.xml',
                'importExampleFormatV1.xml'
            )
        );

        $this->lifecycleEventArgs
            ->expects($this->any())
            ->method('getEntity')
            ->willReturn($import);

        $importEventListener->prePersist($this->lifecycleEventArgs);
        $this->assertNull($import->getFile());
        $this->assertEquals('filename.xml', $import->getFilename());
        $this->assertEquals('xml', $import->getExtension());
        $this->assertEquals('application/xml', $import->getMimeType());
        $this->assertEquals(null, $import->getMediaEnum());
    }

    public function testPrePersistWrongFile()
    {
        $this->manager
            ->expects($this->never())
            ->method('create');

        $importEventListener = new ImportEventListener($this->manager, $this->objectPersister, $this->indexableInterface);

        $import = new Import();
        $import->setFile(null);

        $this->lifecycleEventArgs
            ->expects($this->any())
            ->method('getEntity')
            ->willReturn($import);

        $importEventListener->prePersist($this->lifecycleEventArgs);
    }

    /**
     * @expectedException \Exception
     */
    public function testPrePersistFilenameWrong()
    {
        $this->manager
            ->expects($this->once())
            ->method('create')
            ->willReturn(null);

        $importEventListener = new ImportEventListener($this->manager, $this->objectPersister, $this->indexableInterface);

        $import = new Import();
        $import->setFile(
            new UploadedFile(
                __DIR__.'/../../../Fixtures/importExampleFormatV1.xml',
                'importExampleFormatV1.xml'
            )
        );

        $this->lifecycleEventArgs
            ->expects($this->any())
            ->method('getEntity')
            ->willReturn($import);

        $importEventListener->prePersist($this->lifecycleEventArgs);
    }

    public function testPostFlushFailed()
    {
        $this->manager
            ->expects($this->never())
            ->method('import');

        $importEventListener = new ImportEventListener($this->manager, $this->objectPersister, $this->indexableInterface);
        $importEventListener->postFlush($this->postFlushEventArgs);
    }

    public function testPostFlushSuccess()
    {
        $this->manager
            ->expects($this->once())
            ->method('create')
            ->willReturn('filename.xml');

        $this->repository
            ->expects($this->once())
            ->method('findOneByTemplate')
            ->willReturn(new MimeType);

        $this->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->repository);

        $this->lifecycleEventArgs
            ->expects($this->once())
            ->method('getEntityManager')
            ->willReturn($this->entityManager);

        $importEventListener = new ImportEventListener($this->manager, $this->objectPersister, $this->indexableInterface);

        $import = new Import();
        $import->setFile(
            new UploadedFile(
                __DIR__.'/../../../Fixtures/importExampleFormatV1.xml',
                'importExampleFormatV1.xml'
            )
        );

        $this->lifecycleEventArgs
            ->expects($this->any())
            ->method('getEntity')
            ->willReturn($import);

        $importEventListener->prePersist($this->lifecycleEventArgs);
        $this->assertNull($import->getFile());
        $this->assertEquals('filename.xml', $import->getFilename());
        $this->assertEquals('xml', $import->getExtension());
        $this->assertEquals('application/xml', $import->getMimeType());

        $this->manager
            ->expects($this->once())
            ->method('import');

        $importEventListener->postFlush($this->postFlushEventArgs);
    }

    public function testPostUpdate()
    {
        $this->markTestIncomplete('Test not implemented yet');
    }
}
