<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Tests\Unit\Component\Storage;

use Gaufrette\File;
use Gaufrette\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use UmberFirm\Bundle\SupplierBundle\Component\Storage\ImportStorage;

/**
 * Class ImportStorageTest
 *
 * @package UmberFirm\Bundle\SupplierBundle\Tests\Unit\Component\Storage
 */
class ImportStorageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Filesystem
     */
    private $filesystem;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->filesystem = $this->createMock(Filesystem::class);
    }

    public function testCreateSuccess()
    {
        $this->filesystem
            ->expects($this->once())
            ->method('write')
            ->willReturn(123456);

        $storage = new ImportStorage($this->filesystem);
        $result = $storage->create(
            new UploadedFile(__DIR__.'/../../../Fixtures/importExampleFormatV1.xml', 'importExampleFormatV1.xml')
        );

        $this->assertNotEmpty($result);
    }

    public function testCreateFailed()
    {
        $this->filesystem
            ->expects($this->once())
            ->method('write')
            ->willReturn(false);

        $storage = new ImportStorage($this->filesystem);
        $result = $storage->create(
            new UploadedFile(__DIR__.'/../../../Fixtures/importExampleFormatV1.xml', 'importExampleFormatV1.xml')
        );

        $this->assertNull($result);
    }

    /**
     * @expectedException \TypeError
     */
    public function testCreateTypeError()
    {
        $storage = new ImportStorage($this->filesystem);
        $storage->create(new \stdClass());
    }

    public function testContent()
    {
        $file = new File('key', $this->filesystem);
        $file->setContent('content');

        $this->filesystem
            ->expects($this->once())
            ->method('get')
            ->willReturn($file);

        $storage = new ImportStorage($this->filesystem);
        $content = $storage->getContent('key');
        $this->assertNotNull($content);
        $this->assertEquals('content', $content);
    }

    /**
     * @expectedException \TypeError
     */
    public function testContentTypeError()
    {
        $storage = new ImportStorage($this->filesystem);
        $content = $storage->getContent(123);
    }

    public function testDeleteSuccess()
    {
        $this->filesystem
            ->expects($this->once())
            ->method('delete')
            ->willReturn(true);

        $storage = new ImportStorage($this->filesystem);
        $result = $storage->delete('filename.jpg');

        $this->assertTrue($result);
    }

    public function testDeleteFailed()
    {
        $this->filesystem
            ->expects($this->once())
            ->method('delete')
            ->willReturn(false);

        $storage = new ImportStorage($this->filesystem);
        $result = $storage->delete('filename.jpg');

        $this->assertFalse($result);
    }

    /**
     * @expectedException \TypeError
     */
    public function testDeleteTypeError()
    {
        $storage = new ImportStorage($this->filesystem);
        $content = $storage->delete(123);
    }
}
