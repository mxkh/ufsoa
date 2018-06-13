<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCartItem;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class ShoppingCartTest
 *
 * @package UmberFirm\Bundle\OrderBundle\Tests\Unit\Entity
 */
class ShoppingCartTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ShoppingCart
     */
    private $shoppingCart;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->shoppingCart = new ShoppingCart();
    }

    public function testOrderItems()
    {
        $this->assertInstanceOf(ShoppingCart::class, $this->shoppingCart->addShoppingCartItem(new ShoppingCartItem()));
        $this->assertInstanceOf(ArrayCollection::class, $this->shoppingCart->getShoppingCartItems());
        $this->assertInstanceOf(ShoppingCartItem::class, $this->shoppingCart->getShoppingCartItems()->first());
        $this->assertInstanceOf(
            ShoppingCart::class,
            $this->shoppingCart->removeShoppingCartItem($this->shoppingCart->getShoppingCartItems()->first())
        );
    }

    /**
     * @expectedException \TypeError
     */
    public function testOrderItemsWrongArgumentType()
    {
        $this->assertInstanceOf(ShoppingCart::class, $this->shoppingCart->addShoppingCartItem(null));
    }

    public function testCustomer()
    {
        $this->assertInstanceOf(ShoppingCart::class, $this->shoppingCart->setCustomer(new Customer()));
        $this->assertInstanceOf(Customer::class, $this->shoppingCart->getCustomer());
    }

    /**
     * @expectedException \TypeError
     */
    public function testCustomerWrongArgumentType()
    {
        $this->assertInstanceOf(ShoppingCart::class, $this->shoppingCart->setCustomer(123));
    }

    public function testQuantity()
    {
        $this->assertInstanceOf(ShoppingCart::class, $this->shoppingCart->setQuantity(10));
        $this->assertEquals(10, $this->shoppingCart->getQuantity());
    }

    public function testAmount()
    {
        $this->assertInstanceOf(ShoppingCart::class, $this->shoppingCart->setAmount(100.12));
        $this->assertEquals(100.12, $this->shoppingCart->getAmount());
    }

    public function testShop()
    {
        $this->assertInstanceOf(ShoppingCart::class, $this->shoppingCart->setShop(new Shop()));
        $this->assertInstanceOf(Shop::class, $this->shoppingCart->getShop());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShopWrongArgumentType()
    {
        $this->assertInstanceOf(ShoppingCart::class, $this->shoppingCart->setShop(123));
    }
}
