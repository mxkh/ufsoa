<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\ProductBundle\Entity\Selection;
use UmberFirm\Bundle\ProductBundle\Entity\SelectionItem;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class SelectionTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity
 */
class SelectionTest extends \PHPUnit_Framework_TestCase
{
    /** @var  Selection */
    private $selection;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->selection = new Selection();
    }

    public function testShop()
    {
        $this->assertInstanceOf(Selection::class, $this->selection->setShop(null));
        $this->assertNull($this->selection->getShop());
        $shop = new Shop();
        $this->assertInstanceOf(Selection::class, $this->selection->setShop($shop));
        $this->assertInstanceOf(Shop::class, $this->selection->getShop());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShopWithTypeError()
    {
        $this->selection->setShop(123);
    }

    public function testIsActive()
    {
        $this->assertInstanceOf(Selection::class, $this->selection->setIsActive(true));
        $this->assertTrue($this->selection->getIsActive());
    }

    /**
     * @expectedException \TypeError
     */
    public function testIsActiveWithTypeError()
    {
        $this->selection->setIsActive(123);
    }

    public function testName()
    {
        $this->assertInstanceOf(Selection::class, $this->selection->setName('string', 'en'));
        $this->assertEquals('string', $this->selection->getName());
    }

    /**
     * @expectedException \TypeError
     */
    public function testNameWithWrongNameArgumentType()
    {
        $this->selection->setName(null, 'en');
    }

    public function testDescription()
    {
        $this->assertInstanceOf(Selection::class, $this->selection->setDescription('string', 'en'));
        $this->assertEquals('string', $this->selection->getDescription());
    }

    /**
     * @expectedException \TypeError
     */
    public function testDescriptionWithWrongNameArgumentType()
    {
        $this->selection->setDescription(null, 'en');
    }

    public function testSlug()
    {
        $this->assertInternalType('string', $this->selection->getSlug());
    }

    public function testItem()
    {
        $this->assertInstanceOf(Collection::class, $this->selection->getItems());
        $selectionItem = new SelectionItem();
        $this->assertInstanceOf(Selection::class, $this->selection->addItem($selectionItem));
        $this->assertInstanceOf(Selection::class, $this->selection->removeItem($selectionItem));
    }

    /**
     * @expectedException \TypeError
     */
    public function testAddItemWithTypeError()
    {
        $this->selection->addItem(123);
    }

    /**
     * @expectedException \TypeError
     */
    public function testRemoveItemWithTypeError()
    {
        $this->selection->removeItem(123);
    }
}
