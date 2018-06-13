<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\Tests\Unit\Component\Manager;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use UmberFirm\Bundle\MediaBundle\Component\Manager\MediaManager;
use UmberFirm\Bundle\MediaBundle\Component\Producer\MediaProducerInterface;
use UmberFirm\Bundle\MediaBundle\Component\Storage\MediaStorageInterface;

/**
 * Class MediaManagerTest
 *
 * @package UmberFirm\Bundle\MediaBundle\Tests\Unit\Component\Manager
 */
class MediaManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var MediaStorageInterface */
    private $storage;

    /** @var MediaProducerInterface */
    private $producer;

    /** @var UploadedFile */
    private $file;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->storage = $this->getMockBuilder(MediaStorageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->producer = $this->getMockBuilder(MediaProducerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->file = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testCreateSuccess()
    {
        $this->storage
            ->expects($this->once())
            ->method('create')
            ->willReturn('filename.jpg');

        $manager = new MediaManager($this->storage, $this->producer);
        $result = $manager->create($this->file);

        $this->assertEquals('filename.jpg', $result);
    }

    public function testCreateFailure()
    {
        $this->storage
            ->expects($this->once())
            ->method('create')
            ->willReturn(null);

        $manager = new MediaManager($this->storage, $this->producer);
        $result = $manager->create($this->file);

        $this->assertNull($result);
    }

    /**
     * @expectedException \TypeError
     */
    public function testCreateTypeError()
    {
        $this->storage
            ->expects($this->never())
            ->method('create');

        $manager = new MediaManager($this->storage, $this->producer);
        $manager->create(new \stdClass());
    }

    public function testDelete()
    {
        $this->producer
            ->expects($this->once())
            ->method('delete');

        $manager = new MediaManager($this->storage, $this->producer);
        $manager->delete('filename.jpg');
    }

    /**
     * @expectedException \TypeError
     */
    public function testDeleteTypeError()
    {
        $this->producer
            ->expects($this->never())
            ->method('delete');

        $manager = new MediaManager($this->storage, $this->producer);
        $manager->delete(213);
    }
}
