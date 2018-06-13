<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\ProductBundle\Entity\Attribute;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariantMedia;
use UmberFirm\Bundle\ShopBundle\Entity\Store;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;

/**
 * Class ProductVariantTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity
 */
class ProductVariantTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProductVariant
     */
    private $productVariant;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->productVariant = new ProductVariant();
    }

    public function testProduct()
    {
        $this->assertInstanceOf(ProductVariant::class, $this->productVariant->setProduct(new Product()));
        $this->assertInstanceOf(Product::class, $this->productVariant->getProduct());
    }

    public function testProductNullable()
    {
        $this->productVariant->setProduct(null);
        $this->assertNull($this->productVariant->getProduct());
    }

    /**
     * @expectedException \TypeError
     */
    public function testProductWithWrongArgumentType()
    {
        $this->productVariant->setProduct(123);
    }

    public function testPrice()
    {
        $this->assertInstanceOf(ProductVariant::class, $this->productVariant->setPrice(1.0));
        $this->assertEquals(1.0, $this->productVariant->getPrice());
    }

    public function testPriceNullable()
    {
        $this->productVariant->setPrice(null);
        $this->assertInternalType('float', $this->productVariant->getPrice());
    }

    /**
     * @expectedException \TypeError
     */
    public function testPriceWithWrongArgumentType()
    {
        $this->productVariant->setPrice('string');
    }

    public function testSalePrice()
    {
        $this->assertInstanceOf(ProductVariant::class, $this->productVariant->setSalePrice(1.0));
        $this->assertEquals(1.0, $this->productVariant->getSalePrice());
    }

    public function testSalePriceNullable()
    {
        $this->productVariant->setSalePrice(null);
        $this->assertInternalType('float', $this->productVariant->getSalePrice());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSalePriceWithWrongArgumentType()
    {
        $this->productVariant->setSalePrice('string');
    }

    public function testProductVariantAttribute()
    {
        $this->assertInstanceOf(
            ProductVariant::class,
            $this->productVariant->addProductVariantAttribute(new Attribute())
        );
        $this->assertInstanceOf(Collection::class, $this->productVariant->getProductVariantAttributes());
        $this->assertInstanceOf(Attribute::class, $this->productVariant->getProductVariantAttributes()->first());
        $this->assertEquals(1, $this->productVariant->getProductVariantAttributes()->count());
        $this->assertInstanceOf(
            ProductVariant::class,
            $this->productVariant->removeProductVariantAttribute(
                $this->productVariant->getProductVariantAttributes()->first()
            )
        );
        $this->assertEquals(0, $this->productVariant->getProductVariantAttributes()->count());
    }

    /**
     * @expectedException \TypeError
     */
    public function testFeatureValueWithWrongArgumentType()
    {
        $this->productVariant->addProductVariantAttribute(null);
    }

    public function testMedias()
    {
        $this->assertInstanceOf(ProductVariant::class, $this->productVariant->addMedia(new ProductVariantMedia()));
        $this->assertInstanceOf(ArrayCollection::class, $this->productVariant->getMedias());
        $this->assertInstanceOf(ProductVariantMedia::class, $this->productVariant->getMedias()->first());
        $this->assertEquals(1, $this->productVariant->getMedias()->count());
        $this->assertInstanceOf(
            ProductVariant::class,
            $this->productVariant->removeMedia($this->productVariant->getMedias()->first())
        );
        $this->assertEquals(0, $this->productVariant->getMedias()->count());
    }
}
