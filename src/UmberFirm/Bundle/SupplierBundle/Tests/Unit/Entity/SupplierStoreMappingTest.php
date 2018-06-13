<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\ShopBundle\Entity\Store;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierStoreMapping;

/**
 * Class SupplierStoreMappingTest
 *
 * @package UmberFirm\Bundle\SupplierBundle\Tests\Unit\Entity
 */
class SupplierStoreMappingTest extends \PHPUnit_Framework_TestCase
{
    /** @var SupplierStoreMapping */
    private $storeMapping;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->storeMapping = new SupplierStoreMapping();
    }

    public function testSupplier()
    {
        $supplier = new Supplier();
        $this->assertInstanceOf(SupplierStoreMapping::class, $this->storeMapping->setSupplier($supplier));
        $this->assertEquals($supplier, $this->storeMapping->getSupplier());
        $this->assertInstanceOf(Supplier::class, $this->storeMapping->getSupplier());
    }

    public function testSupplierNullable()
    {
        $supplier = null;
        $this->assertInstanceOf(SupplierStoreMapping::class, $this->storeMapping->setSupplier($supplier));
        $this->assertEquals($supplier, $this->storeMapping->getSupplier());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSupplierTypeError()
    {
        $supplier = new \stdClass();
        $this->storeMapping->setSupplier($supplier);
    }

    public function testSupplierStore()
    {
        $this->assertInternalType('string', $this->storeMapping->getSupplierStore());
        $this->assertInstanceOf(SupplierStoreMapping::class, $this->storeMapping->setSupplierStore('supplier_key'));
        $this->assertEquals('supplier_key', $this->storeMapping->getSupplierStore());
        $this->assertInternalType('string', $this->storeMapping->getSupplierStore());
        $this->assertInstanceOf(SupplierStoreMapping::class, $this->storeMapping->setSupplierStore(null));
        $this->assertInternalType('string', $this->storeMapping->getSupplierStore());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSupplierStoreTypeError()
    {
        $this->storeMapping->setSupplierStore(123);
    }

    public function testStore()
    {
        $store = new Store();
        $this->assertInstanceOf(SupplierStoreMapping::class, $this->storeMapping->setStore($store));
        $this->assertEquals($store, $this->storeMapping->getStore());
        $this->assertInstanceOf(Store::class, $this->storeMapping->getStore());
    }

    public function testStoreNullable()
    {
        $store = null;
        $this->assertInstanceOf(SupplierStoreMapping::class, $this->storeMapping->setStore($store));
        $this->assertEquals($store, $this->storeMapping->getStore());
    }

    /**
     * @expectedException \TypeError
     */
    public function testStoreTypeError()
    {
        $store = new \stdClass();
        $this->storeMapping->setStore($store);
    }
}
