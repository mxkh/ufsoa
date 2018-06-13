<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Unit\DataObject;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\Promocode;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart;
use UmberFirm\Bundle\PublicBundle\DataObject\PublicOrder;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopCurrency;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDelivery;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPayment;

/**
 * Class PublicOrderTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Unit\DataObject
 */
class PublicOrderTest extends \PHPUnit_Framework_TestCase
{
    /** @var PublicOrder */
    private $publicOrder;

    protected function setUp()
    {
        parent::setUp();
        $this->publicOrder = new PublicOrder();
    }

    public function testShoppingCart()
    {
        $this->assertEquals(null, $this->publicOrder->getShoppingCart());
        $this->assertInstanceOf(PublicOrder::class, $this->publicOrder->setShoppingCart(null));
        $this->assertInstanceOf(PublicOrder::class, $this->publicOrder->setShoppingCart(new ShoppingCart()));
        $this->assertInstanceOf(ShoppingCart::class, $this->publicOrder->getShoppingCart());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShoppingCartTypeError()
    {
        $this->publicOrder->setShoppingCart(new \stdClass());
    }

    public function testPromocode()
    {
        $this->assertEquals(null, $this->publicOrder->getPromocode());
        $this->assertInstanceOf(PublicOrder::class, $this->publicOrder->setPromocode(null));
        $this->assertInstanceOf(PublicOrder::class, $this->publicOrder->setPromocode(new Promocode()));
        $this->assertInstanceOf(Promocode::class, $this->publicOrder->getPromocode());
    }

    /**
     * @expectedException \TypeError
     */
    public function testPromocodeTypeError()
    {
        $this->publicOrder->setPromocode(new \stdClass());
    }

    public function testCustomer()
    {
        $this->assertEquals(null, $this->publicOrder->getCustomer());
        $this->assertInstanceOf(PublicOrder::class, $this->publicOrder->setCustomer(null));
        $this->assertInstanceOf(PublicOrder::class, $this->publicOrder->setCustomer(new Customer()));
        $this->assertInstanceOf(Customer::class, $this->publicOrder->getCustomer());
    }

    /**
     * @expectedException \TypeError
     */
    public function testCustomerTypeError()
    {
        $this->publicOrder->setCustomer(new \stdClass());
    }

    public function testShop()
    {
        $this->assertEquals(null, $this->publicOrder->getShop());
        $this->assertInstanceOf(PublicOrder::class, $this->publicOrder->setShop(null));
        $this->assertInstanceOf(PublicOrder::class, $this->publicOrder->setShop(new Shop()));
        $this->assertInstanceOf(Shop::class, $this->publicOrder->getShop());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShopTypeError()
    {
        $this->publicOrder->setShop(new \stdClass());
    }

    public function testShopPayment()
    {
        $this->assertEquals(null, $this->publicOrder->getShopPayment());
        $this->assertInstanceOf(PublicOrder::class, $this->publicOrder->setShopPayment(null));
        $this->assertInstanceOf(PublicOrder::class, $this->publicOrder->setShopPayment(new ShopPayment()));
        $this->assertInstanceOf(ShopPayment::class, $this->publicOrder->getShopPayment());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShopPaymentTypeError()
    {
        $this->publicOrder->setShopPayment(new \stdClass());
    }

    public function testOrderNumber()
    {
        $this->assertInternalType('string', $this->publicOrder->getNumber());
        $this->assertInstanceOf(PublicOrder::class, $this->publicOrder->setNumber('#123'));
        $this->assertEquals('#123', $this->publicOrder->getNumber());
    }

    /**
     * @expectedException \TypeError
     */
    public function testOrderNumberTypeError()
    {
        $this->publicOrder->setNumber(123);
    }

    public function testPaymentUrl()
    {
        $this->assertEmpty($this->publicOrder->getPaymentUrl());
        $this->assertInstanceOf(PublicOrder::class, $this->publicOrder->setPaymentUrl(null));
        $this->assertEmpty( $this->publicOrder->getPaymentUrl());
        $this->assertInstanceOf(PublicOrder::class, $this->publicOrder->setPaymentUrl('string'));
        $this->assertEquals('string', $this->publicOrder->getPaymentUrl());
    }

    /**
     * @expectedException \TypeError
     */
    public function testPaymentUrlTypeError()
    {
        $this->publicOrder->setPaymentUrl(123);
    }

    public function testShopCurrency()
    {
        $this->assertEquals(null, $this->publicOrder->getShopCurrency());
        $this->assertInstanceOf(PublicOrder::class, $this->publicOrder->setShopCurrency(null));
        $this->assertInstanceOf(PublicOrder::class, $this->publicOrder->setShopCurrency(new ShopCurrency()));
        $this->assertInstanceOf(ShopCurrency::class, $this->publicOrder->getShopCurrency());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShopCurrencyTypeError()
    {
        $this->publicOrder->setShopCurrency(new \stdClass());
    }

    public function testShopDelivery()
    {
        $this->assertEquals(null, $this->publicOrder->getShopDelivery());
        $this->assertInstanceOf(PublicOrder::class, $this->publicOrder->setShopDelivery(null));
        $this->assertInstanceOf(PublicOrder::class, $this->publicOrder->setShopDelivery(new ShopDelivery()));
        $this->assertInstanceOf(ShopDelivery::class, $this->publicOrder->getShopDelivery());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShopDeliveryTypeError()
    {
        $this->publicOrder->setShopDelivery(new \stdClass());
    }
}
