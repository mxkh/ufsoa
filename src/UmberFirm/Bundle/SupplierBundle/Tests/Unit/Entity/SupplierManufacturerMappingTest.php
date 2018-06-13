<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierManufacturerMapping;

/**
 * Class SupplierManufacturerMappingTest
 *
 * @package UmberFirm\Bundle\SupplierBundle\Tests\Unit\Entity
 */
class SupplierManufacturerMappingTest extends \PHPUnit_Framework_TestCase
{
    /** @var SupplierManufacturerMapping */
    private $manufacturerMapping;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->manufacturerMapping = new SupplierManufacturerMapping();
    }

    public function testSupplier()
    {
        $supplier = new Supplier();
        $this->assertInstanceOf(SupplierManufacturerMapping::class, $this->manufacturerMapping->setSupplier($supplier));
        $this->assertEquals($supplier, $this->manufacturerMapping->getSupplier());
        $this->assertInstanceOf(Supplier::class, $this->manufacturerMapping->getSupplier());
    }

    public function testSupplierNullable()
    {
        $supplier = null;
        $this->assertInstanceOf(SupplierManufacturerMapping::class, $this->manufacturerMapping->setSupplier($supplier));
        $this->assertEquals($supplier, $this->manufacturerMapping->getSupplier());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSupplierTypeError()
    {
        $supplier = new \stdClass();
        $this->manufacturerMapping->setSupplier($supplier);
    }

    public function testSupplierManufacturer()
    {
        $this->assertInternalType('string', $this->manufacturerMapping->getSupplierManufacturer());
        $this->assertInstanceOf(
            SupplierManufacturerMapping::class,
            $this->manufacturerMapping->setSupplierManufacturer('supplier_key')
        );
        $this->assertEquals('supplier_key', $this->manufacturerMapping->getSupplierManufacturer());
        $this->assertInternalType('string', $this->manufacturerMapping->getSupplierManufacturer());
        $this->assertInstanceOf(
            SupplierManufacturerMapping::class,
            $this->manufacturerMapping->setSupplierManufacturer(null)
        );
        $this->assertInternalType('string', $this->manufacturerMapping->getSupplierManufacturer());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSupplierManufacturerTypeError()
    {
        $this->manufacturerMapping->setSupplierManufacturer(123);
    }

    public function testManufacturer()
    {
        $manufacturer = new Manufacturer();
        $this->assertInstanceOf(
            SupplierManufacturerMapping::class,
            $this->manufacturerMapping->setManufacturer($manufacturer)
        );
        $this->assertEquals($manufacturer, $this->manufacturerMapping->getManufacturer());
        $this->assertInstanceOf(Manufacturer::class, $this->manufacturerMapping->getManufacturer());
    }

    public function testManufacturerNullable()
    {
        $manufacturer = null;
        $this->assertInstanceOf(
            SupplierManufacturerMapping::class,
            $this->manufacturerMapping->setManufacturer($manufacturer)
        );
        $this->assertEquals($manufacturer, $this->manufacturerMapping->getManufacturer());
    }

    /**
     * @expectedException \TypeError
     */
    public function testManufacturerTypeError()
    {
        $manufacturer = new \stdClass();
        $this->manufacturerMapping->setManufacturer($manufacturer);
    }
}
