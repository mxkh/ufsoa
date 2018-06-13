<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Tests\Unit\Component\Manager;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use UmberFirm\Bundle\SupplierBundle\Component\Manager\ImportManager;
use UmberFirm\Bundle\SupplierBundle\Component\Producer\ImportProducerInterface;
use UmberFirm\Bundle\SupplierBundle\Component\Storage\ImportStorageInterface;
use UmberFirm\Bundle\SupplierBundle\Entity\Import;

/**
 * Class ImportManagerTest
 *
 * @package UmberFirm\Bundle\SupplierBundle\Tests\Unit\Component\Manager
 */
class ImportManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|ImportStorageInterface */
    private $storage;

    /** @var \PHPUnit_Framework_MockObject_MockObject|ImportProducerInterface */
    private $producer;

    /** @var \PHPUnit_Framework_MockObject_MockObject|UploadedFile */
    private $file;

    /** @var \PHPUnit_Framework_MockObject_MockObject|Import */
    private $import;

    protected function setUp()
    {
        $this->storage = $this->getMockBuilder(ImportStorageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->producer = $this->getMockBuilder(ImportProducerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->file = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->import = $this->getMockBuilder(Import::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testCreateSuccess()
    {
        $this->storage
            ->expects($this->once())
            ->method('create')
            ->willReturn('new_file_name.xml');

        $manager = new ImportManager($this->storage, $this->producer);
        $result = $manager->create($this->file);

        $this->assertEquals('new_file_name.xml', $result);
    }

    public function testCreateFailed()
    {
        $this->storage
            ->expects($this->once())
            ->method('create')
            ->willReturn(false);

        $manager = new ImportManager($this->storage, $this->producer);
        $result = $manager->create($this->file);

        $this->assertEquals(false, $result);
    }

    /**
     * @expectedException \TypeError
     */
    public function testCreateTypeError()
    {
        $this->storage
            ->expects($this->never())
            ->method('create');

        $manager = new ImportManager($this->storage, $this->producer);
        $manager->create(new \stdClass());
    }

    public function testDelete()
    {
        $this->storage
            ->expects($this->once())
            ->method('delete')
            ->willReturn(true);

        $manager = new ImportManager($this->storage, $this->producer);
        $manager->delete('new_file_name.xml');
    }

    /**
     * @expectedException \TypeError
     */
    public function testDeleteTypeError()
    {
        $this->storage
            ->expects($this->never())
            ->method('delete');

        $manager = new ImportManager($this->storage, $this->producer);
        $manager->delete(123);
    }

    public function testImport()
    {
        $this->producer
            ->expects($this->once())
            ->method('import');

        $manager = new ImportManager($this->storage, $this->producer);
        $manager->import($this->import);
    }

    /**
     * @expectedException \TypeError
     */
    public function testImportTypeError()
    {
        $this->producer
            ->expects($this->never())
            ->method('import');

        $manager = new ImportManager($this->storage, $this->producer);
        $manager->import(new \stdClass());
    }
}
