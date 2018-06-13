<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Tests\Unit\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use UmberFirm\Bundle\MediaBundle\Entity\MediaEnum;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\SupplierBundle\Entity\Import;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;

/**
 * Class ImportTest
 *
 * @package UmberFirm\Bundle\SupplierBundle\Tests\Unit
 */
class ImportTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Import
     */
    private $import;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->import = new Import();
    }

    public function testMediaEnum()
    {
        $mediaEnum = new MediaEnum();
        $this->assertEquals(null, $this->import->getMediaEnum());
        $this->assertInstanceOf(Import::class, $this->import->setMediaEnum($mediaEnum));
        $this->assertEquals($mediaEnum, $this->import->getMediaEnum());
        $this->assertInstanceOf(MediaEnum::class, $this->import->getMediaEnum());
        $this->assertInstanceOf(Import::class, $this->import->setMediaEnum(null));
        $this->assertEquals(null, $this->import->getMediaEnum());
    }

    /**
     * @expectedException \TypeError
     */
    public function testMediaEnumTypeError()
    {
        $this->import->setMediaEnum(new \stdClass());
    }

    public function testVersion()
    {
        $this->assertInternalType('string', $this->import->getVersion());
        $this->assertInstanceOf(Import::class, $this->import->setVersion('v1'));
        $this->assertEquals('v1', $this->import->getVersion());
        $this->assertInternalType('string', $this->import->getVersion());
        $this->assertInstanceOf(Import::class, $this->import->setVersion(null));
        $this->assertInternalType('string', $this->import->getVersion());
    }

    /**
     * @expectedException \TypeError
     */
    public function testVersionTypeError()
    {
        $this->import->setVersion(123);
    }

    public function testSupplier()
    {
        $supplier = new Supplier();
        $this->assertEquals(null, $this->import->getSupplier());
        $this->assertInstanceOf(Import::class, $this->import->setSupplier($supplier));
        $this->assertEquals($supplier, $this->import->getSupplier());
        $this->assertInstanceOf(Supplier::class, $this->import->getSupplier());
        $this->assertInstanceOf(Import::class, $this->import->setSupplier(null));
        $this->assertEquals(null, $this->import->getSupplier());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSupplierTypeError()
    {
        $this->import->setSupplier(new \stdClass());
    }

    public function testShop()
    {
        $shop = new Shop();
        $this->assertEquals(null, $this->import->getShop());
        $this->assertInstanceOf(Import::class, $this->import->setShop($shop));
        $this->assertEquals($shop, $this->import->getShop());
        $this->assertInstanceOf(Shop::class, $this->import->getShop());
        $this->assertInstanceOf(Import::class, $this->import->setShop(null));
        $this->assertEquals(null, $this->import->getShop());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShopTypeError()
    {
        $this->import->setShop(new \stdClass());
    }

    public function testFilename()
    {
        $this->assertInternalType('string', $this->import->getFilename());
        $this->assertInstanceOf(Import::class, $this->import->setFilename('filename.xml'));
        $this->assertEquals('filename.xml', $this->import->getFilename());
        $this->assertInternalType('string', $this->import->getFilename());
        $this->assertInstanceOf(Import::class, $this->import->setFilename(null));
        $this->assertInternalType('string', $this->import->getFilename());
    }

    /**
     * @expectedException \TypeError
     */
    public function testFilenameTypeError()
    {
        $this->import->setFilename(123);
    }

    public function testMimeType()
    {
        $this->assertInternalType('string', $this->import->getMimeType());
        $this->assertInstanceOf(Import::class, $this->import->setMimeType('application/xml'));
        $this->assertEquals('application/xml', $this->import->getMimeType());
        $this->assertInternalType('string', $this->import->getMimeType());
        $this->assertInstanceOf(Import::class, $this->import->setMimeType(null));
        $this->assertInternalType('string', $this->import->getMimeType());
    }

    /**
     * @expectedException \TypeError
     */
    public function testMimeTypeTypeError()
    {
        $this->import->setMimeType(123);
    }

    public function testExtension()
    {
        $this->assertInternalType('string', $this->import->getExtension());
        $this->assertInstanceOf(Import::class, $this->import->setExtension('xml'));
        $this->assertEquals('xml', $this->import->getExtension());
        $this->assertInternalType('string', $this->import->getExtension());
        $this->assertInstanceOf(Import::class, $this->import->setExtension(null));
        $this->assertInternalType('string', $this->import->getExtension());
    }

    /**
     * @expectedException \TypeError
     */
    public function testExtensionTypeError()
    {
        $this->import->setExtension(123);
    }

    public function testStatus()
    {
        $this->assertInternalType('integer', $this->import->getStatus());
        $this->assertInstanceOf(Import::class, $this->import->setStatus(Import::STATUS_CREATED));
        $this->assertEquals(Import::STATUS_CREATED, $this->import->getStatus());
        $this->assertInternalType('integer', $this->import->getStatus());
        $this->assertInstanceOf(Import::class, $this->import->setStatus(null));
        $this->assertInternalType('integer', $this->import->getStatus());
    }

    /**
     * @expectedException \TypeError
     */
    public function testStatusTypeError()
    {
        $this->import->setStatus('string');
    }

    public function testFile()
    {
        $file = new UploadedFile(__DIR__.'/../../Fixtures/importExampleFormatV1.xml', 'importExampleFormatV1.xml');
        $this->assertEquals(null, $this->import->getFile());
        $this->assertInstanceOf(Import::class, $this->import->setFile($file));
        $this->assertEquals($file, $this->import->getFile());
        $this->assertInstanceOf(UploadedFile::class, $this->import->getFile());
        $this->assertInstanceOf(Import::class, $this->import->setFile(null));
        $this->assertEquals(null, $this->import->getFile());
    }

    /**
     * @expectedException \TypeError
     */
    public function testFileTypeError()
    {
        $this->import->setFile(new \stdClass());
    }

    public function testGetStatusesAndVersions()
    {
        $this->assertInternalType('array', $this->import->getStatuses());
        $this->assertInternalType('array', $this->import->getVersions());
    }
}
