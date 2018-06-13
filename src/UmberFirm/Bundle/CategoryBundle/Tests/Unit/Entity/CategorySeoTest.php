<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CategoryBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\CategoryBundle\Entity\CategorySeo;
use UmberFirm\Bundle\CategoryBundle\Entity\Category;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class CategorySeoTest
 *
 * @package UmberFirm\Bundle\CategoryBundle\Tests\Unit\Entity
 */
class CategorySeoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CategorySeo
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
        $this->seo = new CategorySeo();
        $this->locale = $this->seo->getDefaultLocale();
    }

    /**
     * @expectedException \TypeError
     */
    public function testKeywords()
    {
        $this->assertInternalType('string', $this->seo->getKeywords());
        $this->assertInstanceOf(CategorySeo::class, $this->seo->setKeywords('keywords', $this->locale));
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
        $this->assertInstanceOf(CategorySeo::class, $this->seo->setTitle('Title', $this->locale));
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
        $this->assertInstanceOf(CategorySeo::class, $this->seo->setDescription('Description', $this->locale));
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

    public function testCategory()
    {
        $category = new Category();
        $this->assertEquals(null, $this->seo->getCategory());
        $this->assertInstanceOf(CategorySeo::class, $this->seo->setCategory($category));
        $this->assertEquals($category, $this->seo->getCategory());
        $this->seo->setCategory(null);
    }

    /**
     * @expectedException \TypeError
     */
    public function testCategoryTypeError()
    {
        $this->seo->setCategory(new \stdClass());
    }

    public function testShop()
    {
        $shop = new Shop();
        $this->assertEquals(null, $this->seo->getShop());
        $this->assertInstanceOf(CategorySeo::class, $this->seo->setShop($shop));
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
