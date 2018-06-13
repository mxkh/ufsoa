<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\Order;
use UmberFirm\Bundle\OrderBundle\Entity\OrderItem;
use UmberFirm\Bundle\OrderBundle\Entity\Promocode;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopCurrency;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDelivery;

/**
 * Class OrderTest
 *
 * @package UmberFirm\Bundle\OrderBundle\Tests\Unit\Entity
 */
class OrderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Order
     */
    private $order;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->order = new Order();
    }

    public function testOrderItems()
    {
        $this->assertInstanceOf(Order::class, $this->order->addOrderItem(new OrderItem()));
        $this->assertInstanceOf(ArrayCollection::class, $this->order->getOrderItems());
        $this->assertInstanceOf(OrderItem::class, $this->order->getOrderItems()->first());
        $this->assertInstanceOf(Order::class, $this->order->removeOrderItem($this->order->getOrderItems()->first()));
    }

    /**
     * @expectedException \TypeError
     */
    public function testOrderItemsWrongArgumentType()
    {
        $this->assertInstanceOf(Order::class, $this->order->addOrderItem(null));
    }

    public function testCustomer()
    {
        $this->assertInstanceOf(Order::class, $this->order->setCustomer(new Customer()));
        $this->assertInstanceOf(Customer::class, $this->order->getCustomer());
    }

    /**
     * @expectedException \TypeError
     */
    public function testCustomerWrongArgumentType()
    {
        $this->assertInstanceOf(Order::class, $this->order->setCustomer(123));
    }

    public function testQuantity()
    {
        $this->assertInstanceOf(Order::class, $this->order->setQuantity(10));
        $this->assertEquals(10, $this->order->getQuantity());
    }

    public function testAmount()
    {
        $this->assertInstanceOf(Order::class, $this->order->setAmount(100.12));
        $this->assertEquals(100.12, $this->order->getAmount());
    }

    public function testShop()
    {
        $this->assertInstanceOf(Order::class, $this->order->setShop(new Shop()));
        $this->assertInstanceOf(Shop::class, $this->order->getShop());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShopWrongArgumentType()
    {
        $this->assertInstanceOf(Order::class, $this->order->setShop(123));
    }

    public function testPromocode()
    {
        $promocode = new Promocode();
        $this->assertNull($this->order->getPromocode());
        $this->assertInstanceOf(Order::class, $this->order->setPromocode(null));
        $this->assertInstanceOf(Order::class, $this->order->setPromocode($promocode));
        $this->assertEquals($promocode, $this->order->getPromocode());
    }

    /**
     * @expectedException \TypeError
     */
    public function testPromocodeTypeError()
    {
        $this->order->setPromocode(new \stdClass());
    }

    public function testPaymentUrl()
    {
        $this->assertEmpty($this->order->getPaymentUrl());
        $this->assertInstanceOf(Order::class, $this->order->setPaymentUrl(null));
        $this->assertInstanceOf(Order::class, $this->order->setPaymentUrl('some url'));
        $this->assertEquals('some url', $this->order->getPaymentUrl());
    }

    /**
     * @expectedException \TypeError
     */
    public function testPaymentUrlTypeError()
    {
        $this->order->setPaymentUrl(231231);
    }

    public function testNumber()
    {
        $this->order->setNumber(null);
        $this->assertInternalType('string', $this->order->getNumber());
        $this->assertInstanceOf(Order::class, $this->order->setNumber('#1234444'));
        $this->assertEquals('#1234444', $this->order->getNumber());
    }

    /**
     * @expectedException \TypeError
     */
    public function testNumberTypeError()
    {
        $this->order->setNumber(123);
    }

    public function testShopCurrency()
    {
        $promocode = new ShopCurrency();
        $this->assertNull($this->order->getShopCurrency());
        $this->assertInstanceOf(Order::class, $this->order->setShopCurrency(null));
        $this->assertInstanceOf(Order::class, $this->order->setShopCurrency($promocode));
        $this->assertEquals($promocode, $this->order->getShopCurrency());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShopCurrencyTypeError()
    {
        $this->order->setShopCurrency(new \stdClass());
    }

    public function testShopDelivery()
    {
        $this->assertEquals(null, $this->order->getShopDelivery());
        $this->assertInstanceOf(Order::class, $this->order->setShopDelivery(null));
        $this->assertInstanceOf(Order::class, $this->order->setShopDelivery(new ShopDelivery()));
        $this->assertInstanceOf(ShopDelivery::class, $this->order->getShopDelivery());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShopDeliveryTypeError()
    {
        $this->order->setShopDelivery(new \stdClass());
    }
}
