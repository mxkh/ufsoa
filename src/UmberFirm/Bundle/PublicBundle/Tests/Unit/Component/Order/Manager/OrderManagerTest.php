<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Order\Manager;

use Doctrine\ORM\EntityManagerInterface;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerAddress;
use UmberFirm\Bundle\OrderBundle\Entity\Order;
use UmberFirm\Bundle\OrderBundle\Entity\Promocode;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart;
use UmberFirm\Bundle\PublicBundle\Component\Order\Builder\OrderBuilderInterface;
use UmberFirm\Bundle\PublicBundle\Component\Order\Manager\OrderManager;
use UmberFirm\Bundle\PublicBundle\Component\Order\Producer\OrderProducerInterface;
use UmberFirm\Bundle\PublicBundle\DataObject\PublicOrder;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopCurrency;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDelivery;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPayment;

/**
 * Class OrderManager
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Order\Manager
 */
class OrderManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|OrderBuilderInterface $promocodeFilterFactory */
    private $orderBuilder;
    /** @var \PHPUnit_Framework_MockObject_MockObject|EntityManagerInterface $promocodeFilterFactory */
    private $entityManager;
    /** @var \PHPUnit_Framework_MockObject_MockObject|OrderProducerInterface */
    private $orderProducer;

    protected function setUp()
    {
        $this->orderBuilder = $this->createMock(OrderBuilderInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->orderProducer = $this->createMock(OrderProducerInterface::class);
    }

    public function testManage()
    {
        $this->orderProducer->expects($this->once())->method('sendOrder');

        /** @var \PHPUnit_Framework_MockObject_MockObject|PublicOrder $publicOrder */
        $publicOrder = $this->createMock(PublicOrder::class);
        $publicOrder->expects($this->any())->method('getShoppingCart')->willReturn(new ShoppingCart());
        $publicOrder->expects($this->once())->method('getShop')->willReturn(new Shop());
        $publicOrder->expects($this->once())->method('getCustomer')->willReturn(new Customer());
        $publicOrder->expects($this->once())->method('getPromocode')->willReturn(new Promocode());
        $publicOrder->expects($this->once())->method('getShopPayment')->willReturn(new ShopPayment());
        $publicOrder->expects($this->once())->method('getShopCurrency')->willReturn(new ShopCurrency());
        $publicOrder->expects($this->once())->method('getShopDelivery')->willReturn(new ShopDelivery());
        $publicOrder->expects($this->once())->method('getNote')->willReturn('string');
        $publicOrder->expects($this->once())->method('createCustomerAddress')->willReturn(new CustomerAddress());

        /** @var \PHPUnit_Framework_MockObject_MockObject|Order $order */
        $order = $this->createMock(Order::class);
        $order->expects($this->once())->method('getNumber')->willReturn('#22323');
        $order->expects($this->once())->method('getPaymentUrl')->willReturn('wfp.service.url');

        $this->entityManager->expects($this->any())->method('persist');
        $this->entityManager->expects($this->once())->method('flush');

        $this->mockOrderBuilder($order);
        $orderManager = new OrderManager($this->entityManager, $this->orderBuilder, $this->orderProducer);
        $this->assertInstanceOf(PublicOrder::class, $orderManager->manage($publicOrder));
    }

    public function testBuildOrder()
    {
        $publicOrder = $this->createMock(PublicOrder::class);
        $publicOrder->expects($this->any())->method('getShoppingCart')->willReturn(new ShoppingCart());
        $publicOrder->expects($this->once())->method('getShop')->willReturn(new Shop());
        $publicOrder->expects($this->once())->method('getCustomer')->willReturn(new Customer());
        $publicOrder->expects($this->once())->method('getPromocode')->willReturn(new Promocode());
        $publicOrder->expects($this->once())->method('getShopPayment')->willReturn(new ShopPayment());
        $publicOrder->expects($this->once())->method('getShopCurrency')->willReturn(new ShopCurrency());
        $publicOrder->expects($this->once())->method('getShopDelivery')->willReturn(new ShopDelivery());
        $publicOrder->expects($this->once())->method('createCustomerAddress')->willReturn(new CustomerAddress());
        $this->mockOrderBuilder(new Order());
        $orderManager = new OrderManager($this->entityManager, $this->orderBuilder, $this->orderProducer);
        $this->assertInstanceOf(Order::class, $orderManager->buildOrder($publicOrder));
    }

    public function testPrepareOrderPublic()
    {
        $publicOrder = $this->createMock(PublicOrder::class);
        $order = $this->createMock(Order::class);
        $order->expects($this->once())->method('getNumber')->willReturn('#22323');
        $order->expects($this->once())->method('getPaymentUrl')->willReturn('wfp.service.url');
        $orderManager = new OrderManager($this->entityManager, $this->orderBuilder, $this->orderProducer);
        $this->assertInstanceOf(PublicOrder::class, $orderManager->prepareOrderPublic($publicOrder, $order));
    }

    protected function mockOrderBuilder($order)
    {
        $this->orderBuilder->expects($this->once())->method('createOrder')->willReturnSelf();
        $this->orderBuilder->expects($this->once())->method('addOrderItems')->willReturnSelf();
        $this->orderBuilder->expects($this->once())->method('addShop')->willReturnSelf();
        $this->orderBuilder->expects($this->once())->method('addCustomer')->willReturnSelf();
        $this->orderBuilder->expects($this->once())->method('addCurrency')->willReturnSelf();
        $this->orderBuilder->expects($this->once())->method('addPromocode')->willReturnSelf();
        $this->orderBuilder->expects($this->once())->method('addPayment')->willReturnSelf();
        $this->orderBuilder->expects($this->once())->method('addDelivery')->willReturnSelf();
        $this->orderBuilder->expects($this->once())->method('addShippingAddress')->willReturnSelf();
        $this->orderBuilder->expects($this->once())->method('addNote')->willReturnSelf();
        $this->orderBuilder->expects($this->once())->method('getOrder')->willReturn($order);
    }
}
