<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\ProductBundle\Entity\ProductMedia;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariantMedia;

/**
 * Class ProductVariantMediaTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity
 */
class ProductVariantMediaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProductVariantMedia
     */
    private $productVariantMedia;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->productVariantMedia = new ProductVariantMedia();
    }

    public function testProductMedia()
    {
        $this->assertInstanceOf(
            ProductVariantMedia::class,
            $this->productVariantMedia->setProductMedia(new ProductMedia())
        );
        $this->assertInstanceOf(ProductMedia::class, $this->productVariantMedia->getProductMedia());
    }

    public function testProductVariant()
    {
        $this->assertInstanceOf(
            ProductVariantMedia::class,
            $this->productVariantMedia->setProductVariant(new ProductVariant())
        );
        $this->assertInstanceOf(ProductVariant::class, $this->productVariantMedia->getProductVariant());
    }

    public function testPosition()
    {
        $this->assertInstanceOf(ProductVariantMedia::class, $this->productVariantMedia->setPosition(5));
        $this->assertEquals(5, $this->productVariantMedia->getPosition());
    }

    public function testPositionNullable()
    {
        $this->productVariantMedia->setPosition(null);
        $this->assertInternalType('int', $this->productVariantMedia->getPosition());
    }

    /**
     * @expectedException \TypeError
     */
    public function testPositionWithWrongArgumentType()
    {
        $this->productVariantMedia->setPosition('1');
    }
}
