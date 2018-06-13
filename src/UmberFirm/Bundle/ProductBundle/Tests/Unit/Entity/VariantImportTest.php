<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\VariantImport;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;

/**
 * Class VariantImportTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity
 */
class VariantImportTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var VariantImport
     */
    private $variantImport;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->variantImport = new VariantImport();
    }

    public function testSupplierReference()
    {
        $this->assertInstanceOf(VariantImport::class, $this->variantImport->setSupplierReference('SupplierReference'));
        $this->assertEquals('SupplierReference', $this->variantImport->getSupplierReference());
    }

    public function testSupplierReferenceNullable()
    {
        $this->variantImport->setSupplierReference(null);
        $this->assertInternalType('string', $this->variantImport->getSupplierReference());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSupplierReferenceWithWrongArgumentType()
    {
        $this->variantImport->setSupplierReference(123);
    }

    public function testProductVariant()
    {
        $this->assertInstanceOf(VariantImport::class, $this->variantImport->setProductVariant(new ProductVariant()));
        $this->assertInstanceOf(ProductVariant::class, $this->variantImport->getProductVariant());
    }

    public function testProductVariantNullable()
    {
        $this->variantImport->setProductVariant(null);
        $this->assertNull($this->variantImport->getProductVariant());
    }

    /**
     * @expectedException \TypeError
     */
    public function testProductVariantWithWrongArgumentType()
    {
        $this->variantImport->setProductVariant(123);
    }

    public function testSupplier()
    {
        $this->assertInstanceOf(VariantImport::class, $this->variantImport->setSupplier(new Supplier()));
        $this->assertInstanceOf(Supplier::class, $this->variantImport->getSupplier());
    }

    public function testSupplierNullable()
    {
        $this->variantImport->setSupplier(null);
        $this->assertNull($this->variantImport->getSupplier());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSupplierWithWrongArgumentType()
    {
        $this->variantImport->setSupplier(123);
    }

    public function testShop()
    {
        $this->assertInstanceOf(VariantImport::class, $this->variantImport->setShop(new Shop()));
        $this->assertInstanceOf(Shop::class, $this->variantImport->getShop());
    }

    public function testShopNullable()
    {
        $this->variantImport->setShop(null);
        $this->assertNull($this->variantImport->getShop());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShopWithWrongArgumentType()
    {
        $this->variantImport->setShop(123);
    }
}
