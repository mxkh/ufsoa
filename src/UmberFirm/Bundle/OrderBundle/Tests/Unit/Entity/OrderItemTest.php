<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\OrderBundle\Entity\Order;
use UmberFirm\Bundle\OrderBundle\Entity\OrderItem;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;

/**
 * Class OrderItemTest
 *
 * @package UmberFirm\Bundle\OrderBundle\Tests\Unit\Entity
 */
class OrderItemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OrderItem
     */
    private $orderItem;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->orderItem = new OrderItem();
    }

    public function testOrder()
    {
        $this->assertInstanceOf(OrderItem::class, $this->orderItem->setOrder(new Order()));
        $this->assertInstanceOf(Order::class, $this->orderItem->getOrder());
    }

    /**
     * @expectedException \TypeError
     */
    public function testOrderWrongArgumentType()
    {
        $this->assertInstanceOf(OrderItem::class, $this->orderItem->setOrder(213));
    }

    public function testQuantity()
    {
        $this->assertInstanceOf(OrderItem::class, $this->orderItem->setQuantity(1));
        $this->assertEquals(1, $this->orderItem->getQuantity());
    }

    public function testPrice()
    {
        $this->assertInstanceOf(OrderItem::class, $this->orderItem->setPrice(100.12));
        $this->assertEquals(100.12, $this->orderItem->getPrice());
    }

    public function testAmount()
    {
        $this->assertInstanceOf(OrderItem::class, $this->orderItem->setAmount(100.12));
        $this->assertEquals(100.12, $this->orderItem->getAmount());
    }

    public function testProductVariant()
    {
        $this->assertInstanceOf(OrderItem::class, $this->orderItem->setProductVariant(new ProductVariant()));
        $this->assertInstanceOf(ProductVariant::class, $this->orderItem->getProductVariant());
    }

    /**
     * @expectedException \TypeError
     */
    public function testProductVariantWrongArgumentType()
    {
        $this->assertInstanceOf(OrderItem::class, $this->orderItem->setProductVariant(123));
    }
}
