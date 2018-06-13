<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductSeo;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class ProductSeoTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity
 */
class ProductSeoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProductSeo
     */
    private $seo;

    /**
     * @var string
     */
    private $locale;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->seo = new ProductSeo();
        $this->locale = $this->seo->getDefaultLocale();
    }

    /**
     * @expectedException \TypeError
     */
    public function testKeywords()
    {
        $this->assertInternalType('string', $this->seo->getKeywords());
        $this->assertInstanceOf(ProductSeo::class, $this->seo->setKeywords('keywords', $this->locale));
        $this->assertEquals('keywords', $this->seo->getKeywords());
        $this->seo->setKeywords(null, $this->locale);
    }

    /**
     * @expectedException \TypeError
     */
    public function testKeywordsLocale()
    {
        $this->seo->setKeywords('string', 123);
    }

    /**
     * @expectedException \TypeError
     */
    public function testTitle()
    {
        $this->assertInternalType('string', $this->seo->getTitle());
        $this->assertInstanceOf(ProductSeo::class, $this->seo->setTitle('Title', $this->locale));
        $this->assertEquals('Title', $this->seo->getTitle());
        $this->seo->setTitle(null, $this->locale);
    }

    /**
     * @expectedException \TypeError
     */
    public function testTitleLocale()
    {
        $this->seo->setTitle('string', 123);
    }

    /**
     * @expectedException \TypeError
     */
    public function testDescription()
    {
        $this->assertInternalType('string', $this->seo->getDescription());
        $this->assertInstanceOf(ProductSeo::class, $this->seo->setDescription('Description', $this->locale));
        $this->assertEquals('Description', $this->seo->getDescription());
        $this->seo->setDescription(null, $this->locale);
    }

    /**
     * @expectedException \TypeError
     */
    public function testTitleDescription()
    {
        $this->seo->setDescription('string', 123);
    }

    public function testProduct()
    {
        $product = new Product();
        $this->assertEquals(null, $this->seo->getProduct());
        $this->assertInstanceOf(ProductSeo::class, $this->seo->setProduct($product));
        $this->assertEquals($product, $this->seo->getProduct());
        $this->seo->setProduct(null);
    }

    /**
     * @expectedException \TypeError
     */
    public function testProductTypeError()
    {
        $this->seo->setProduct(new \stdClass());
    }

    public function testShop()
    {
        $shop = new Shop();
        $this->assertEquals(null, $this->seo->getShop());
        $this->assertInstanceOf(ProductSeo::class, $this->seo->setShop($shop));
        $this->assertEquals($shop, $this->seo->getShop());
        $this->seo->setShop(null);
    }

    /**
     * @expectedException \TypeError
     */
    public function testShopTypeError()
    {
        $this->seo->setShop(new \stdClass());
    }
}
