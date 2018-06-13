<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Tests\Unit\Component\Factory;

use Doctrine\ORM\EntityManagerInterface;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Component\Factory\OrderFactory;
use UmberFirm\Bundle\OrderBundle\Entity\Order;
use UmberFirm\Bundle\OrderBundle\Entity\OrderItem;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCartItem;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\PublicBundle\Component\Order\DataTransferObject\FastOrderDTO;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class OrderFactoryTest
 *
 * @package UmberFirm\Bundle\OrderBundle\Tests\Unit\Component\Factory
 */
class OrderFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $entityManager;

    /**
     * @var ShoppingCart|\PHPUnit_Framework_MockObject_MockObject
     */
    private $shoppingCart;

    protected function setUp()
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->shoppingCart = $this->createMock(ShoppingCart::class);
    }

    public function testCreateOrder()
    {
        $this->shoppingCart->expects($this->once())->method('getQuantity')->willReturn(5);
        $this->shoppingCart->expects($this->once())->method('getAmount')->willReturn(200.92);
        $this->shoppingCart->expects($this->once())->method('getShop')->willReturn(new Shop());

        $orderFactory = new OrderFactory($this->entityManager);
        $order = $orderFactory->createFromCart($this->shoppingCart);
        $this->assertInstanceOf(Order::class, $order);
    }

    /**
     * @expectedException \TypeError
     */
    public function testCreateOrderTypeError()
    {
        $orderFactory = new OrderFactory($this->entityManager);
        $orderFactory->createFromCart(new \stdClass());
    }

    public function testCreateOrderItem()
    {
        $item = $this->createMock(ShoppingCartItem::class);
        $item->expects($this->any())->method('getProductVariant')->willReturn(new ProductVariant());
        $item->expects($this->any())->method('getQuantity')->willReturn(rand(0,10));
        $item->expects($this->any())->method('getAmount')->willReturn(3002.23);
        $item->expects($this->any())->method('getPrice')->willReturn(654);

        $orderFactory = new OrderFactory($this->entityManager);
        $order = $orderFactory->createOrderItem($item);
        $this->assertInstanceOf(OrderItem::class, $order);
    }

    public function testCreateFormFastOrderDTO()
    {
        $fastOrderDTO = $this->createMock(FastOrderDTO::class);

        $this->entityManager->expects($this->at(0))->method('find')->willReturn(new Customer());
        $this->entityManager->expects($this->at(1))->method('find')->willReturn(new Shop());
        $this->entityManager->expects($this->at(2))->method('find')->willReturn(new ProductVariant());

        $orderFactory = new OrderFactory($this->entityManager);
        $order = $orderFactory->createFromFastOrderDTO($fastOrderDTO);
        $this->assertInstanceOf(Order::class, $order);
    }
}
