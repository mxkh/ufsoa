<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCartItem;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;

/**
 * Class ShoppingCartItemTest
 *
 * @package UmberFirm\Bundle\OrderBundle\Tests\Unit\Entity
 */
class ShoppingCartItemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ShoppingCartItem
     */
    private $shoppingCartItem;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->shoppingCartItem = new ShoppingCartItem();
    }

    public function testOrder()
    {
        $this->assertInstanceOf(ShoppingCartItem::class, $this->shoppingCartItem->setShoppingCart(new ShoppingCart()));
        $this->assertInstanceOf(ShoppingCart::class, $this->shoppingCartItem->getShoppingCart());
    }

    /**
     * @expectedException \TypeError
     */
    public function testOrderWrongArgumentType()
    {
        $this->assertInstanceOf(ShoppingCartItem::class, $this->shoppingCartItem->setShoppingCart(213));
    }

    public function testQuantity()
    {
        $this->assertInstanceOf(ShoppingCartItem::class, $this->shoppingCartItem->setQuantity(1));
        $this->assertEquals(1, $this->shoppingCartItem->getQuantity());
    }

    public function testPrice()
    {
        $this->assertInstanceOf(ShoppingCartItem::class, $this->shoppingCartItem->setPrice(100.12));
        $this->assertEquals(100.12, $this->shoppingCartItem->getPrice());
    }

    public function testAmount()
    {
        $this->assertInstanceOf(ShoppingCartItem::class, $this->shoppingCartItem->setAmount(100.12));
        $this->assertEquals(100.12, $this->shoppingCartItem->getAmount());
    }

    public function testProductVariant()
    {
        $this->assertInstanceOf(ShoppingCartItem::class, $this->shoppingCartItem->setProductVariant(new ProductVariant()));
        $this->assertInstanceOf(ProductVariant::class, $this->shoppingCartItem->getProductVariant());
    }

    /**
     * @expectedException \TypeError
     */
    public function testProductVariantWrongArgumentType()
    {
        $this->shoppingCartItem->setProductVariant(123);
    }
}
