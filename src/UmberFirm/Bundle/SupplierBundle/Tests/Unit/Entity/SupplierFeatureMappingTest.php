<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\ProductBundle\Entity\FeatureValue;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierFeatureMapping;

/**
 * Class SupplierFeatureMappingTest
 *
 * @package UmberFirm\Bundle\SupplierBundle\Tests\Unit\Entity
 */
class SupplierFeatureMappingTest extends \PHPUnit_Framework_TestCase
{
    /** @var SupplierFeatureMapping */
    private $featureMapping;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->featureMapping = new SupplierFeatureMapping();
    }

    public function testSupplier()
    {
        $supplier = new Supplier();
        $this->assertInstanceOf(SupplierFeatureMapping::class, $this->featureMapping->setSupplier($supplier));
        $this->assertEquals($supplier, $this->featureMapping->getSupplier());
        $this->assertInstanceOf(Supplier::class, $this->featureMapping->getSupplier());
    }

    public function testSupplierNullable()
    {
        $supplier = null;
        $this->assertInstanceOf(SupplierFeatureMapping::class, $this->featureMapping->setSupplier($supplier));
        $this->assertEquals($supplier, $this->featureMapping->getSupplier());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSupplierTypeError()
    {
        $supplier = new \stdClass();
        $this->featureMapping->setSupplier($supplier);
    }

    public function testSupplierFeatureKey()
    {
        $this->assertEquals(null, $this->featureMapping->getSupplierFeatureKey());
        $this->assertInstanceOf(SupplierfeatureMapping::class, $this->featureMapping->setSupplierFeatureKey('supplier_key'));
        $this->assertEquals('supplier_key', $this->featureMapping->getSupplierFeatureKey());
        $this->assertInternalType('string', $this->featureMapping->getSupplierFeatureKey());
        $this->assertInstanceOf(SupplierfeatureMapping::class, $this->featureMapping->setSupplierFeatureKey(null));
        $this->assertEquals(null, $this->featureMapping->getSupplierFeatureKey());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSupplierFeatureKeyTypeError()
    {
        $this->featureMapping->setSupplierFeatureKey(123);
    }

    public function testSupplierFeatureValue()
    {
        $this->assertEquals(null, $this->featureMapping->getSupplierFeatureValue());
        $this->assertInstanceOf(
            SupplierfeatureMapping::class,
            $this->featureMapping->setSupplierFeatureValue('supplier_key')
        );
        $this->assertEquals('supplier_key', $this->featureMapping->getSupplierFeatureValue());
        $this->assertInternalType('string', $this->featureMapping->getSupplierFeatureValue());
        $this->assertInstanceOf(
            SupplierfeatureMapping::class,
            $this->featureMapping->setSupplierFeatureValue(null)
        );
        $this->assertEquals(null, $this->featureMapping->getSupplierFeatureValue());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSupplierFeatureValueTypeError()
    {
        $this->featureMapping->setSupplierFeatureValue(123);
    }

    public function testFeatureValue()
    {
        $featureValue = new FeatureValue();
        $this->assertInstanceOf(
            SupplierFeatureMapping::class,
            $this->featureMapping->setFeatureValue($featureValue)
        );
        $this->assertEquals($featureValue, $this->featureMapping->getFeatureValue());
        $this->assertInstanceOf(FeatureValue::class, $this->featureMapping->getFeatureValue());
    }

    public function testFeatureValueNullable()
    {
        $featureValue = null;
        $this->assertInstanceOf(
            SupplierFeatureMapping::class,
            $this->featureMapping->setFeatureValue($featureValue)
        );
        $this->assertEquals($featureValue, $this->featureMapping->getFeatureValue());
    }

    /**
     * @expectedException \TypeError
     */
    public function testAttributeTypeError()
    {
        $featureValue = new \stdClass();
        $this->featureMapping->setFeatureValue($featureValue);
    }
}
