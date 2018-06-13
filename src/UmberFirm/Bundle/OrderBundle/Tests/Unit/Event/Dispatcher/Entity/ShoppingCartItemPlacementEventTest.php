<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Tests\Unit\Event\Dispatcher\Entity;

use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCartItem;
use UmberFirm\Bundle\OrderBundle\Event\Dispatcher\Entity\ShoppingCartItemPlacementEvent;
use UmberFirm\Bundle\OrderBundle\Event\Dispatcher\Entity\ShoppingCartItemPlacementEventInterface;

/**
 * Class ShoppingCartItemPlacementEventTest
 *
 * @package UmberFirm\Bundle\OrderBundle\Tests\Unit\Event\Dispatcher\Entity
 */
class ShoppingCartItemPlacementEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ShoppingCartItemPlacementEventInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $event;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->event = $this->getMockBuilder(ShoppingCartItemPlacementEventInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['setShoppingCartItem', 'getShoppingCartItem'])
            ->getMock();
    }

    public function testEventShoppingCartItemPlacement()
    {
        $this->markTestSkipped('deprecated');
        $event = new ShoppingCartItemPlacementEvent;
        $this->assertInstanceOf(
            ShoppingCartItemPlacementEventInterface::class,
            $event->setShoppingCartItem(new ShoppingCartItem())
        );
        $this->assertInstanceOf(ShoppingCartItem::class, $event->getShoppingCartItem());
    }

    /**
     * @expectedException \TypeError
     */
    public function testEventShoppingCartItemPlacementWithWrongEntity()
    {
        $this->markTestSkipped('deprecated');
        $event = new ShoppingCartItemPlacementEvent;
        $event->setShoppingCartItem(new \stdClass());
    }
}
