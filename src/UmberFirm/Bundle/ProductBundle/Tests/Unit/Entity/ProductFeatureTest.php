<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\ProductBundle\Entity\Feature;
use UmberFirm\Bundle\ProductBundle\Entity\FeatureValue;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductFeature;

/**
 * Class ProductFeatureTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity
 */
class ProductFeatureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProductFeature
     */
    private $productFeature;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->productFeature = new ProductFeature();
    }

    public function testFeatureValue()
    {
        $this->assertInstanceOf(
            ProductFeature::class,
            $this->productFeature->addProductFeatureValue(new FeatureValue())
        );
        $this->assertInstanceOf(Collection::class, $this->productFeature->getProductFeatureValues());
        $this->assertInstanceOf(FeatureValue::class, $this->productFeature->getProductFeatureValues()->first());
        $this->assertEquals(1, $this->productFeature->getProductFeatureValues()->count());
        $this->assertInstanceOf(
            ProductFeature::class,
            $this->productFeature->removeProductFeatureValue($this->productFeature->getProductFeatureValues()->first())
        );
        $this->assertEquals(0, $this->productFeature->getProductFeatureValues()->count());
    }

    /**
     * @expectedException \TypeError
     */
    public function testFeatureValueWithWrongArgumentType()
    {
        $this->productFeature->addProductFeatureValue(null);
    }

    public function testProduct()
    {
        $this->assertInstanceOf(ProductFeature::class, $this->productFeature->setProduct(new Product()));
        $this->assertInstanceOf(Product::class, $this->productFeature->getProduct());
    }

    public function testProductNullable()
    {
        $this->productFeature->setProduct(null);
        $this->assertNull($this->productFeature->getProduct());
    }

    /**
     * @expectedException \TypeError
     */
    public function testProductWithWrongArgumentType()
    {
        $this->productFeature->setProduct(123);
    }

    public function testFeature()
    {
        $this->assertInstanceOf(ProductFeature::class, $this->productFeature->setFeature(new Feature()));
        $this->assertInstanceOf(Feature::class, $this->productFeature->getFeature());
    }

    public function testFeatureNullable()
    {
        $this->productFeature->setFeature(null);
        $this->assertNull($this->productFeature->getFeature());
    }

    /**
     * @expectedException \TypeError
     */
    public function testFeatureWithWrongArgumentType()
    {
        $this->productFeature->setFeature(123);
    }
}
