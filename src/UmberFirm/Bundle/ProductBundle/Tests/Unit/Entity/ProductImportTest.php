<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductImport;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;

/**
 * Class ProductImportTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity
 */
class ProductImportTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProductImport
     */
    private $productImport;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->productImport = new ProductImport();
    }

    public function testSupplierReference()
    {
        $this->assertInstanceOf(ProductImport::class, $this->productImport->setSupplierReference('SupplierReference'));
        $this->assertEquals('SupplierReference', $this->productImport->getSupplierReference());
    }

    public function testSupplierReferenceNullable()
    {
        $this->productImport->setSupplierReference(null);
        $this->assertInternalType('string', $this->productImport->getSupplierReference());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSupplierReferenceWithWrongArgumentType()
    {
        $this->productImport->setSupplierReference(123);
    }

    public function testProduct()
    {
        $this->assertInstanceOf(ProductImport::class, $this->productImport->setProduct(new Product()));
        $this->assertInstanceOf(Product::class, $this->productImport->getProduct());
    }

    public function testProductNullable()
    {
        $this->productImport->setProduct(null);
        $this->assertNull($this->productImport->getProduct());
    }

    /**
     * @expectedException \TypeError
     */
    public function testProductWithWrongArgumentType()
    {
        $this->productImport->setProduct(123);
    }

    public function testSupplier()
    {
        $this->assertInstanceOf(ProductImport::class, $this->productImport->setSupplier(new Supplier()));
        $this->assertInstanceOf(Supplier::class, $this->productImport->getSupplier());
    }

    public function testSupplierNullable()
    {
        $this->productImport->setSupplier(null);
        $this->assertNull($this->productImport->getSupplier());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSupplierWithWrongArgumentType()
    {
        $this->productImport->setSupplier(123);
    }

    public function testShop()
    {
        $this->assertInstanceOf(ProductImport::class, $this->productImport->setShop(new Shop()));
        $this->assertInstanceOf(Shop::class, $this->productImport->getShop());
    }

    public function testShopNullable()
    {
        $this->productImport->setShop(null);
        $this->assertNull($this->productImport->getShop());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShopWithWrongArgumentType()
    {
        $this->productImport->setShop(123);
    }
}
