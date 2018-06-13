<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\ProductBundle\Entity\Attribute;
use UmberFirm\Bundle\ProductBundle\Entity\Department;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariantMedia;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\Store;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;

/**
 * Class DepartmentTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity
 */
class DepartmentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Department
     */
    private $department;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->department = new Department();
    }

    public function testProduct()
    {
        $this->assertInstanceOf(Department::class, $this->department->setProductVariant(new ProductVariant()));
        $this->assertInstanceOf(ProductVariant::class, $this->department->getProductVariant());
    }

    public function testProductNullable()
    {
        $this->department->setProductVariant(null);
        $this->assertNull($this->department->getProductVariant());
    }

    /**
     * @expectedException \TypeError
     */
    public function testProductWithWrongArgumentType()
    {
        $this->department->setProductVariant(123);
    }

    public function testArticle()
    {
        $this->assertInstanceOf(Department::class, $this->department->setArticle('article'));
        $this->assertEquals('article', $this->department->getArticle());
    }

    public function testArticleNullable()
    {
        $this->department->setArticle(null);
        $this->assertInternalType('string', $this->department->getArticle());
    }

    /**
     * @expectedException \TypeError
     */
    public function testArticleWithWrongArgumentType()
    {
        $this->department->setArticle(123);
    }

    public function testEan13()
    {
        $this->assertInstanceOf(Department::class, $this->department->setEan13('article'));
        $this->assertEquals('article', $this->department->getEan13());
    }

    public function testEan13Nullable()
    {
        $this->department->setEan13(null);
        $this->assertInternalType('string', $this->department->getEan13());
    }

    /**
     * @expectedException \TypeError
     */
    public function testEan13WithWrongArgumentType()
    {
        $this->department->setEan13(123);
    }

    public function testUpc()
    {
        $this->assertInstanceOf(Department::class, $this->department->setUpc('article'));
        $this->assertEquals('article', $this->department->getUpc());
    }

    public function testUpcNullable()
    {
        $this->department->setUpc(null);
        $this->assertInternalType('string', $this->department->getUpc());
    }

    /**
     * @expectedException \TypeError
     */
    public function testUpcWithWrongArgumentType()
    {
        $this->department->setUpc(123);
    }

    public function testPrice()
    {
        $this->assertInstanceOf(Department::class, $this->department->setPrice(1.0));
        $this->assertEquals(1.0, $this->department->getPrice());
    }

    public function testPriceNullable()
    {
        $this->department->setPrice(null);
        $this->assertInternalType('float', $this->department->getPrice());
    }

    /**
     * @expectedException \TypeError
     */
    public function testPriceWithWrongArgumentType()
    {
        $this->department->setPrice('string');
    }

    public function testSalePrice()
    {
        $this->assertInstanceOf(Department::class, $this->department->setSalePrice(1.0));
        $this->assertEquals(1.0, $this->department->getSalePrice());
    }

    public function testSalePriceNullable()
    {
        $this->department->setSalePrice(null);
        $this->assertInternalType('float', $this->department->getSalePrice());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSalePriceWithWrongArgumentType()
    {
        $this->department->setSalePrice('string');
    }

    public function testQuantity()
    {
        $this->assertInstanceOf(Department::class, $this->department->setQuantity(1));
        $this->assertEquals(1.0, $this->department->getQuantity());
    }

    public function testQuantityNullable()
    {
        $this->department->setQuantity(null);
        $this->assertInternalType('int', $this->department->getQuantity());
    }

    /**
     * @expectedException \TypeError
     */
    public function testQuantityWithWrongArgumentType()
    {
        $this->department->setQuantity('string');
    }

    public function testSupplier()
    {
        $this->assertInstanceOf(Department::class, $this->department->setSupplier(new Supplier()));
        $this->assertInstanceOf(Supplier::class, $this->department->getSupplier());
    }

    public function testSupplierNullable()
    {
        $this->department->setSupplier(null);
        $this->assertNull($this->department->getSupplier());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSupplierWithWrongArgumentType()
    {
        $this->department->setSupplier(123);
    }

    public function testStore()
    {
        $this->assertInstanceOf(Department::class, $this->department->setStore(new Store()));
        $this->assertInstanceOf(Store::class, $this->department->getStore());
    }

    public function testStoreNullable()
    {
        $this->department->setStore(null);
        $this->assertNull($this->department->getStore());
    }

    /**
     * @expectedException \TypeError
     */
    public function testStoreWithWrongArgumentType()
    {
        $this->department->setStore(123);
    }

    public function testShop()
    {
        $this->assertInstanceOf(Department::class, $this->department->setShop(new Shop()));
        $this->assertInstanceOf(Shop::class, $this->department->getShop());
    }

    public function testShopNullable()
    {
        $this->department->setShop(null);
        $this->assertNull($this->department->getShop());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShopWithWrongArgumentType()
    {
        $this->department->setShop(123);
    }
}
