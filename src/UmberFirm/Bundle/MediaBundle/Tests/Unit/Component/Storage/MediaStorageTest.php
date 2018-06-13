<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\Tests\Unit\Component\Storage;

use Gaufrette\Adapter\AwsS3;
use Gaufrette\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use UmberFirm\Bundle\MediaBundle\Component\Storage\MediaStorage;

/**
 * Class MediaStorageTest
 *
 * @package UmberFirm\Bundle\MediaBundle\Tests\Unit\Component\Storage
 */
class MediaStorageTest extends \PHPUnit_Framework_TestCase
{
    /** @var Filesystem */
    private $filesystem;

    /** @var AwsS3 */
    private $adapter;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->filesystem = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->adapter = $this->getMockBuilder(AwsS3::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testCreateSuccess()
    {
        $this->filesystem
            ->expects($this->once())
            ->method('getAdapter')
            ->willReturn($this->adapter);

        $this->adapter
            ->expects($this->once())
            ->method('setMetadata');

        $this->adapter
            ->expects($this->once())
            ->method('write')
            ->willReturn(123456);

        $storage = new MediaStorage($this->filesystem);
        $result = $storage->create(new UploadedFile(__DIR__.'/../../../Functional/Fixtures/1.jpg', '1.jpg'));

        $this->assertNotEmpty($result);
    }

    public function testCreateFailure()
    {
        $this->filesystem
            ->expects($this->once())
            ->method('getAdapter')
            ->willReturn($this->adapter);

        $this->adapter
            ->expects($this->once())
            ->method('setMetadata');

        $this->adapter
            ->expects($this->once())
            ->method('write')
            ->willReturn(false);

        $storage = new MediaStorage($this->filesystem);
        $result = $storage->create(new UploadedFile(__DIR__.'/../../../Functional/Fixtures/1.jpg', '1.jpg'));

        $this->assertNull($result);
    }

    /**
     * @expectedException \TypeError
     */
    public function testCreateTypeError()
    {
        $this->filesystem
            ->expects($this->never())
            ->method('getAdapter');

        $storage = new MediaStorage($this->filesystem);
        $storage->create(new \stdClass());
    }

    public function testDeleteSuccess()
    {
        $this->filesystem
            ->expects($this->once())
            ->method('getAdapter')
            ->willReturn($this->adapter);

        $this->adapter
            ->expects($this->once())
            ->method('delete')
            ->willReturn(true);

        $storage = new MediaStorage($this->filesystem);
        $result = $storage->delete('filename.jpg');

        $this->assertTrue($result);
    }

    public function testDeleteFailure()
    {
        $this->filesystem
            ->expects($this->once())
            ->method('getAdapter')
            ->willReturn($this->adapter);

        $this->adapter
            ->expects($this->once())
            ->method('delete')
            ->willReturn(false);

        $storage = new MediaStorage($this->filesystem);
        $result = $storage->delete('filename.jpg');

        $this->assertFalse($result);
    }

    /**
     * @expectedException \TypeError
     */
    public function testDeleteTypeError()
    {
        $this->filesystem
            ->expects($this->never())
            ->method('getAdapter');

        $storage = new MediaStorage($this->filesystem);
        $storage->delete(123);
    }
}
