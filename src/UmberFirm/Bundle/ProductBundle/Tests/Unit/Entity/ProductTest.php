<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\CategoryBundle\Entity\Category;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductFeature;
use UmberFirm\Bundle\ProductBundle\Entity\ProductMedia;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * TODO: fix tests
 *
 * Class ProductTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity
 */
class ProductTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Product
     */
    private $product;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->product = new Product();
    }

    public function testCategories()
    {
        $this->assertInstanceOf(Product::class, $this->product->addCategory(new Category()));
        $this->assertInstanceOf(ArrayCollection::class, $this->product->getCategories());
        $this->assertInstanceOf(Category::class, $this->product->getCategories()->first());
        $this->assertInstanceOf(Product::class, $this->product->removeCategory($this->product->getCategories()->first()));
        $this->assertEquals(0, $this->product->getCategories()->count());
    }

    public function testMedias()
    {
        $this->assertInstanceOf(Product::class, $this->product->addMedia(new ProductMedia()));
        $this->assertInstanceOf(ArrayCollection::class, $this->product->getMedias());
        $this->assertInstanceOf(ProductMedia::class, $this->product->getMedias()->first());
        $this->assertEquals(1, $this->product->getMedias()->count());
        $this->assertInstanceOf(Product::class, $this->product->removeMedia($this->product->getMedias()->first()));
        $this->assertEquals(0, $this->product->getMedias()->count());
    }

    public function testName()
    {
        $this->assertInstanceOf(Product::class, $this->product->setName('string', 'en'));
        $this->assertEquals('string', $this->product->getName());
    }

    public function testSlug()
    {
        $this->assertInternalType('string', $this->product->getSlug());
    }

    /**
     * @expectedException \TypeError
     */
    public function testNameWithWrongNameArgumentType()
    {
        $this->product->setName(null, 'en');
    }

    /**
     * @expectedException \TypeError
     */
    public function testNameWithWrongLocaleArgumentType()
    {
        $this->product->setName('string', null);
    }

    public function testDescription()
    {
        $this->assertInstanceOf(Product::class, $this->product->setDescription('string', 'en'));
        $this->assertEquals('string', $this->product->getDescription());
    }

    /**
     * @expectedException \TypeError
     */
    public function testDescriptionWithWrongDescriptionArgumentType()
    {
        $this->product->setDescription(null, 'en');
    }

    /**
     * @expectedException \TypeError
     */
    public function testDescriptionWithWrongLocaleArgumentType()
    {
        $this->product->setDescription('string', null);
    }

    public function testShortDescription()
    {
        $this->assertInstanceOf(Product::class, $this->product->setShortDescription('string', 'en'));
        $this->assertEquals('string', $this->product->getShortDescription());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShortDescriptionWithWrongShortDescriptionArgumentType()
    {
        $this->product->setShortDescription(null, 'en');
    }

    /**
     * @expectedException \TypeError
     */
    public function testShortDescriptionWithWrongLocaleArgumentType()
    {
        $this->product->setShortDescription('string', null);
    }

    public function testIsActive()
    {
        $this->assertInstanceOf(Product::class, $this->product->setOutOfStock(true));
        $this->assertTrue($this->product->isOutOfStock());
    }

    /**
     * @expectedException \TypeError
     */
    public function testIsActiveWithWrongArgumentType()
    {
        $this->product->setOutOfStock(null);
    }

    public function testOutOfStock()
    {
        $this->assertInstanceOf(Product::class, $this->product->setOutOfStock(true));
        $this->assertTrue($this->product->isOutOfStock());
    }

    /**
     * @expectedException \TypeError
     */
    public function testOutOfStockWithWrongArgumentType()
    {
        $this->product->setOutOfStock(null);
    }

    public function testHidden()
    {
        $this->assertInstanceOf(Product::class, $this->product->setIsHidden(true));
        $this->assertTrue($this->product->isHidden());
    }

    /**
     * @expectedException \TypeError
     */
    public function testHiddenWithWrongArgumentType()
    {
        $this->product->setIsHidden(null);
    }

    public function testSalePrice()
    {
        $this->assertInstanceOf(Product::class, $this->product->setSalePrice(1.0));
        $this->assertEquals(1.0, $this->product->getSalePrice());
    }

    public function testSalePriceNullable()
    {
        $this->product->setSalePrice(null);
        $this->assertInternalType('float', $this->product->getSalePrice());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSalePriceWithWrongArgumentType()
    {
        $this->product->setSalePrice('string');
    }

    public function testPrice()
    {
        $this->assertInstanceOf(Product::class, $this->product->setPrice(1.0));
        $this->assertEquals(1.0, $this->product->getPrice());
    }

    public function testPriceNullable()
    {
        $this->product->setPrice(null);
        $this->assertInternalType('float', $this->product->getPrice());
    }

    /**
     * @expectedException \TypeError
     */
    public function testPriceWithWrongArgumentType()
    {
        $this->product->setPrice('string');
    }

    public function testShop()
    {
        $this->product->setShop(null);
        $this->assertNull($this->product->getShop());
        $this->assertInstanceOf(Product::class, $this->product->setShop(new Shop()));
        $this->assertInstanceOf(Shop::class, $this->product->getShop());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShopWithWrongArgumentType()
    {
        $this->product->setShop(new \stdClass());
    }

    public function testProductImport()
    {
        $this->product->setManufacturer(null);
        $this->assertNull($this->product->getManufacturer());
        $this->assertInstanceOf(Product::class, $this->product->setManufacturer(new Manufacturer()));
        $this->assertInstanceOf(Manufacturer::class, $this->product->getManufacturer());
    }

    /**
     * @expectedException \TypeError
     */
    public function testProductImportWithWrongArgumentType()
    {
        $this->product->setManufacturer(new \stdClass());
    }

    public function testProductFeature()
    {
        $this->assertInstanceOf(Product::class, $this->product->addProductFeature(new ProductFeature()));
        $this->assertInstanceOf(Collection::class, $this->product->getProductFeatures());
        $this->assertInstanceOf(ProductFeature::class, $this->product->getProductFeatures()->first());
        $this->assertEquals(1, $this->product->getProductFeatures()->count());
        $this->assertInstanceOf(
            Product::class,
            $this->product->removeProductFeature($this->product->getProductFeatures()->first())
        );
        $this->assertEquals(0, $this->product->getProductFeatures()->count());
    }

    /**
     * @expectedException \TypeError
     */
    public function testProductFeatureWithWrongArgumentType()
    {
        $this->product->addProductFeature(null);
    }

    public function testProductVariant()
    {
        $this->assertInstanceOf(Product::class, $this->product->addProductVariant(new ProductVariant()));
        $this->assertInstanceOf(Collection::class, $this->product->getProductVariants());
        $this->assertInstanceOf(ProductVariant::class, $this->product->getProductVariants()->first());
        $this->assertEquals(1, $this->product->getProductVariants()->count());
        $this->assertInstanceOf(
            Product::class,
            $this->product->removeProductVariant($this->product->getProductVariants()->first())
        );
        $this->assertEquals(0, $this->product->getProductVariants()->count());
    }

    /**
     * @expectedException \TypeError
     */
    public function testProductVariantWithWrongArgumentType()
    {
        $this->product->addProductVariant(null);
    }
}
