<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\MediaBundle\Entity\Media;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductMedia;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class ProductMediaTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity
 */
class ProductMediaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProductMedia
     */
    private $productMedia;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->productMedia = new ProductMedia();
    }

    public function testProduct()
    {
        $this->assertInstanceOf(ProductMedia::class, $this->productMedia->setProduct(new Product()));
        $this->assertInstanceOf(Product::class, $this->productMedia->getProduct());
    }

    public function testShop()
    {
        $this->assertInstanceOf(ProductMedia::class, $this->productMedia->setShop(new Shop()));
        $this->assertInstanceOf(Shop::class, $this->productMedia->getShop());
    }

    public function testMedia()
    {
        $this->assertInstanceOf(ProductMedia::class, $this->productMedia->setMedia(new Media()));
        $this->assertInstanceOf(Media::class, $this->productMedia->getMedia());
    }

    public function testAlt()
    {
        $this->assertInstanceOf(ProductMedia::class, $this->productMedia->setAlt('Some title', 'en'));
        $this->assertEquals('Some title', $this->productMedia->getAlt());
    }

    public function testPosition()
    {
        $this->assertInstanceOf(ProductMedia::class, $this->productMedia->setPosition(5));
        $this->assertEquals(5, $this->productMedia->getPosition());
    }

    public function testPositionNullable()
    {
        $this->productMedia->setPosition(null);
        $this->assertInternalType('int', $this->productMedia->getPosition());
    }

    /**
     * @expectedException \TypeError
     */
    public function testPositionWithWrongArgumentType()
    {
        $this->productMedia->setPosition('1');
    }
}
