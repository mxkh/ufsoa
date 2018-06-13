<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\Selection;
use UmberFirm\Bundle\ProductBundle\Entity\SelectionItem;

/**
 * Class SelectionItemTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity
 */
class SelectionItemTest extends \PHPUnit_Framework_TestCase
{
    /** @var  SelectionItem */
    private $item;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->item = new SelectionItem();
    }

    public function testSelection()
    {
        $this->assertInstanceOf(SelectionItem::class, $this->item->setSelection(null));
        $this->assertNull($this->item->getSelection());
        $selection = new Selection();
        $this->assertInstanceOf(SelectionItem::class, $this->item->setSelection($selection));
        $this->assertInstanceOf(Selection::class, $this->item->getSelection());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSelectionWithTypeError()
    {
        $this->item->setSelection(123);
    }

    public function testProduct()
    {
        $this->assertInstanceOf(SelectionItem::class, $this->item->setProduct(null));
        $this->assertNull($this->item->getProduct());
        $product = new Product();
        $this->assertInstanceOf(SelectionItem::class, $this->item->setProduct($product));
        $this->assertInstanceOf(Product::class, $this->item->getProduct());
    }

    /**
     * @expectedException \TypeError
     */
    public function testProductWithTypeError()
    {
        $this->item->setProduct(123);
    }
}
