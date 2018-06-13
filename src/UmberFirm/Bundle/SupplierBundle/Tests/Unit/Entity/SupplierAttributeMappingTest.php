<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\ProductBundle\Entity\Attribute;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierAttributeMapping;

/**
 * Class SupplierAttributeMappingTest
 *
 * @package UmberFirm\Bundle\SupplierBundle\Tests\Unit\Entity
 */
class SupplierAttributeMappingTest extends \PHPUnit_Framework_TestCase
{
    /** @var SupplierAttributeMapping */
    private $attributeMapping;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->attributeMapping = new SupplierAttributeMapping();
    }

    public function testSupplier()
    {
        $supplier = new Supplier();
        $this->assertInstanceOf(SupplierAttributeMapping::class, $this->attributeMapping->setSupplier($supplier));
        $this->assertEquals($supplier, $this->attributeMapping->getSupplier());
        $this->assertInstanceOf(Supplier::class, $this->attributeMapping->getSupplier());
    }

    public function testSupplierNullable()
    {
        $supplier = null;
        $this->assertInstanceOf(SupplierAttributeMapping::class, $this->attributeMapping->setSupplier($supplier));
        $this->assertEquals($supplier, $this->attributeMapping->getSupplier());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSupplierTypeError()
    {
        $supplier = new \stdClass();
        $this->attributeMapping->setSupplier($supplier);
    }

    public function testSupplierAttributeKey()
    {
        $this->assertInternalType('string', $this->attributeMapping->getSupplierAttributeKey());
        $this->assertInstanceOf(
            SupplierAttributeMapping::class,
            $this->attributeMapping->setSupplierAttributeKey('supplier_key')
        );
        $this->assertEquals('supplier_key', $this->attributeMapping->getSupplierAttributeKey());
        $this->assertInternalType('string', $this->attributeMapping->getSupplierAttributeKey());
        $this->assertInstanceOf(
            SupplierAttributeMapping::class,
            $this->attributeMapping->setSupplierAttributeKey(null)
        );
        $this->assertInternalType('string', $this->attributeMapping->getSupplierAttributeKey());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSupplierAttributeKeyTypeError()
    {
        $this->attributeMapping->setSupplierAttributeKey(123);
    }

    public function testSupplierAttributeValue()
    {
        $this->assertInternalType('string', $this->attributeMapping->getSupplierAttributeValue());
        $this->assertInstanceOf(
            SupplierAttributeMapping::class,
            $this->attributeMapping->setSupplierAttributeValue('supplier_value')
        );
        $this->assertEquals('supplier_value', $this->attributeMapping->getSupplierAttributeValue());
        $this->assertInternalType('string', $this->attributeMapping->getSupplierAttributeValue());
        $this->assertInstanceOf(
            SupplierAttributeMapping::class,
            $this->attributeMapping->setSupplierAttributeValue(null)
        );
        $this->assertInternalType('string', $this->attributeMapping->getSupplierAttributeValue());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSupplierAttributeValueTypeError()
    {
        $this->attributeMapping->setSupplierAttributeValue(123);
    }

    public function testAttribute()
    {
        $attribute = new Attribute();
        $this->assertInstanceOf(SupplierAttributeMapping::class, $this->attributeMapping->setAttribute($attribute));
        $this->assertEquals($attribute, $this->attributeMapping->getAttribute());
        $this->assertInstanceOf(Attribute::class, $this->attributeMapping->getAttribute());
    }

    public function testAttributeNullable()
    {
        $attribute = null;
        $this->assertInstanceOf(SupplierAttributeMapping::class, $this->attributeMapping->setAttribute($attribute));
        $this->assertEquals($attribute, $this->attributeMapping->getAttribute());
    }

    /**
     * @expectedException \TypeError
     */
    public function testAttributeTypeError()
    {
        $attribute = new \stdClass();
        $this->attributeMapping->setAttribute($attribute);
    }
}
