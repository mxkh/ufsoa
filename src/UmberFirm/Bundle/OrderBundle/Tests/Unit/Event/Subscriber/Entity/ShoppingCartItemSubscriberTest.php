<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Tests\Unit\Event\Subscriber\Entity;

use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCartItem;
use UmberFirm\Bundle\OrderBundle\Event\Dispatcher\Entity\ShoppingCartItemPlacementEventInterface;
use UmberFirm\Bundle\OrderBundle\Event\Subscriber\Entity\ShoppingCartItemSubscriber;

/**
 * Class ShoppingCartItemSubscriberTest
 *
 * @package UmberFirm\Bundle\OrderBundle\Tests\Unit\Event\Subscriber
 */
class ShoppingCartItemSubscriberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ShoppingCartItemProducerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $producer;

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

    public function testSubscriber()
    {
        $this->markTestSkipped('deprecated');
        $this->event->expects($this->once())
            ->method('getShoppingCartItem')
            ->willReturn(new ShoppingCartItem());

        $subscriber = new ShoppingCartItemSubscriber($this->producer);
        $subscriber->onPlacement($this->event);

        $this->assertArrayHasKey(ShoppingCartItemPlacementEventInterface::NAME, $subscriber->getSubscribedEvents());
        $this->assertEquals(
            'onPlacement',
            $subscriber->getSubscribedEvents()[ShoppingCartItemPlacementEventInterface::NAME]
        );
    }

    /**
     * @expectedException \TypeError
     */
    public function testWrongEntity()
    {
        $this->markTestSkipped('deprecated');
        $this->event->expects($this->once())
            ->method('getShoppingCartItem')
            ->willReturn(new \stdClass());

        $this->producer->expects($this->never())
            ->method('save');

        $subscriber = new ShoppingCartItemSubscriber($this->producer);
        $subscriber->onPlacement($this->event);
    }
}
