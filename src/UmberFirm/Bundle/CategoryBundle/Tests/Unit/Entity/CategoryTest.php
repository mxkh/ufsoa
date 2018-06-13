<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CategoryBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use UmberFirm\Bundle\CategoryBundle\Entity\Category;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class CategoryTest
 *
 * @package UmberFirm\Bundle\CategoryBundle\Tests\Unit\Entity
 */
class CategoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Category
     */
    private $category;

    protected function setUp()
    {
        $this->category = new Category();
    }

    public function testLeft()
    {
        $this->assertInstanceOf(Category::class, $this->category->setLeft(1));
        $this->assertEquals(1, $this->category->getLeft());
    }

    /**
     * @expectedException \TypeError
     */
    public function testLeftWithWrongArgumentType()
    {
        $this->category->setLeft('1');
    }

    public function testRight()
    {
        $this->assertInstanceOf(Category::class, $this->category->setRight(3));
        $this->assertEquals(3, $this->category->getRight());
    }

    /**
     * @expectedException \TypeError
     */
    public function testRightWithWrongArgumentType()
    {
        $this->category->setRight('3');
    }

    public function testRoot()
    {
        $this->assertInstanceOf(Category::class, $this->category->setRoot('string'));
        $this->assertEquals('string', $this->category->getRoot());
    }

    /**
     * @expectedException \TypeError
     */
    public function testRootWithWrongArgumentType()
    {
        $this->category->setRoot(123);
    }

    public function testLevel()
    {
        $this->assertInstanceOf(Category::class, $this->category->setLevel(5));
        $this->assertEquals(5, $this->category->getLevel());
    }

    /**
     * @expectedException \TypeError
     */
    public function testLevelWithWrongArgumentType()
    {
        $this->category->setLeft('5');
    }

    public function testPosition()
    {
        $this->assertInstanceOf(Category::class, $this->category->setPosition(3));
        $this->assertEquals(3, $this->category->getPosition());
    }

    /**
     * @expectedException \TypeError
     */
    public function testPositionWithWrongArgumentType()
    {
        $this->category->setPosition('3');
    }

    public function testTitle()
    {
        $this->assertInstanceOf(Category::class, $this->category->setTitle('Some title', 'en'));
        $this->assertEquals('Some title', $this->category->getTitle());
    }

    /**
     * @expectedException \TypeError
     */
    public function testTitleWithWrongTitleArgumentType()
    {
        $this->category->setTitle(123, 'str');
    }

    /**
     * @expectedException \TypeError
     */
    public function testTitleWithWrongLocaleArgumentType()
    {
        $this->category->setTitle('str', 123);
    }

    public function testDescription()
    {
        $this->assertInstanceOf(Category::class, $this->category->setDescription('Veery big description', 'en'));
        $this->assertEquals('Veery big description', $this->category->getDescription());
    }

    /**
     * @expectedException \TypeError
     */
    public function testDescriptionWithWrongDescriptionArgumentType()
    {
        $this->category->setTitle(123, 'str');
    }

    /**
     * @expectedException \TypeError
     */
    public function testDescriptionWithWrongLocaleArgumentType()
    {
        $this->category->setTitle('str', 123);
    }

    public function testParent()
    {
        $this->assertInstanceOf(Category::class, $this->category->setParent(new Category()));
        $this->assertInstanceOf(Category::class, $this->category->getParent());
    }

    /**
     * @expectedException \TypeError
     */
    public function testParentWithWrongArgumentType()
    {
        $this->category->setParent(123);
    }

    public function testChildren()
    {
        $this->assertInstanceOf(Category::class, $this->category->setChildren(new Category()));
        $this->assertInstanceOf(Category::class, $this->category->getChildren());
    }

    /**
     * @expectedException \TypeError
     */
    public function testChildrenWithWrongArgumentType()
    {
        $this->category->setChildren(123);
    }

    public function testShop()
    {
        $this->assertInstanceOf(Category::class, $this->category->setShop(new Shop()));
        $this->assertInstanceOf(Shop::class, $this->category->getShop());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShopWithWrongArgumentType()
    {
        $this->category->setShop(123);
    }

    public function testProduct()
    {
        $this->assertInstanceOf(Category::class, $this->category->addProduct(new Product()));
        $this->assertInstanceOf(ArrayCollection::class, $this->category->getProducts());
        $this->assertInstanceOf(Product::class, $this->category->getProducts()->first());
    }

    /**
     * @expectedException \TypeError
     */
    public function testProductWithWrongArgumentType()
    {
        $this->category->addProduct(123);
    }
}
