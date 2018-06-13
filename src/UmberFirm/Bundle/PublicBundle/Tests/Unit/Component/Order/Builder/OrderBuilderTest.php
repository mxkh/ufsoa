<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Order\Builder;

use Doctrine\Common\Collections\ArrayCollection;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Component\Factory\OrderFactoryInterface;
use UmberFirm\Bundle\OrderBundle\Entity\Order;
use UmberFirm\Bundle\OrderBundle\Entity\Promocode;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCartItem;
use UmberFirm\Bundle\PaymentBundle\Component\Manager\PaymentManagerInterface;
use UmberFirm\Bundle\PublicBundle\Component\Order\Builder\OrderBuilder;
use UmberFirm\Bundle\PublicBundle\Component\Order\Builder\OrderBuilderInterface;
use UmberFirm\Bundle\PublicBundle\Component\Order\Manager\PromocodeManagerInterface;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopCurrency;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDelivery;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPayment;

/**
 * Class OrderBuilderTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Order\Builder
 */
class OrderBuilderTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \PHPUnit_Framework_MockObject_MockObject|OrderFactoryInterface */
    private $orderFactory;

    /** @var  \PHPUnit_Framework_MockObject_MockObject|PromocodeManagerInterface */
    private $promocodeManager;

    /** @var  \PHPUnit_Framework_MockObject_MockObject|PaymentManagerInterface */
    private $paymentManager;

    /** @var  OrderBuilderInterface */
    private $orderBuilder;

    protected function setUp()
    {
        $this->orderFactory = $this->createMock(OrderFactoryInterface::class);
        $this->promocodeManager = $this->createMock(PromocodeManagerInterface::class);
        $this->paymentManager = $this->createMock(PaymentManagerInterface::class);
        $order = new Order();
        $order->setAmount(23.32);
        $this->orderFactory->expects($this->once())->method('createFromCart')->willReturn($order);
        $this->orderBuilder = new OrderBuilder($this->orderFactory, $this->promocodeManager, $this->paymentManager);
    }

    public function testCreateOrder()
    {
        $this->assertInstanceOf(OrderBuilderInterface::class, $this->orderBuilder->createOrder(new ShoppingCart()));
        $this->assertInstanceOf(Order::class, $this->orderBuilder->getOrder());
    }

    public function testAddOrderItems()
    {
        $this->orderBuilder->createOrder(new ShoppingCart());
        $this->assertInstanceOf(Order::class, $this->orderBuilder->getOrder());
        $this->assertInstanceOf(
            OrderBuilderInterface::class,
            $this->orderBuilder->addOrderItems(new ArrayCollection([new ShoppingCartItem()]))
        );
    }

    public function testAddShop()
    {
        $this->orderBuilder->createOrder(new ShoppingCart());
        $this->assertInstanceOf(
            OrderBuilderInterface::class,
            $this->orderBuilder->addShop(new Shop())
        );
    }

    public function testAddCustomer()
    {
        $this->orderBuilder->createOrder(new ShoppingCart());
        $this->assertInstanceOf(
            OrderBuilderInterface::class,
            $this->orderBuilder->addCustomer(new Customer())
        );
    }

    public function testAddCurrency()
    {
        $this->orderBuilder->createOrder(new ShoppingCart());
        $this->assertInstanceOf(
            OrderBuilderInterface::class,
            $this->orderBuilder->addCurrency(new ShopCurrency())
        );
    }

    public function testAddPromocodeNull()
    {
        $this->orderBuilder->createOrder(new ShoppingCart());
        $this->assertInstanceOf(
            OrderBuilderInterface::class,
            $this->orderBuilder->addPromocode(null)
        );
    }

    public function testAddPromocode()
    {
        $this->orderBuilder->createOrder(new ShoppingCart());
        $this->assertInstanceOf(
            OrderBuilderInterface::class,
            $this->orderBuilder->addPromocode(new Promocode())
        );
    }

    public function testAddPayment()
    {
        $this->orderBuilder->createOrder(new ShoppingCart());
        $this->assertInstanceOf(
            OrderBuilderInterface::class,
            $this->orderBuilder->addPayment(new ShopPayment())
        );
    }

    public function testAddDelivery()
    {
        $this->orderBuilder->createOrder(new ShoppingCart());
        $this->assertInstanceOf(
            OrderBuilderInterface::class,
            $this->orderBuilder->addDelivery(new ShopDelivery())
        );
    }
}
