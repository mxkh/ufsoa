<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopGroup;

/**
 * Class ShopGroupTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity
 */
class ShopGroupTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ShopGroup
     */
    private $shopGroup;

    /**
     *{@inheritdoc}
     */
    protected function setUp()
    {
        $this->shopGroup = new ShopGroup();
    }

    public function testShop()
    {
        $shop = new Shop();
        $this->assertInstanceOf(ShopGroup::class, $this->shopGroup->addShop($shop));
        $this->assertInstanceOf(Collection::class, $this->shopGroup->getShops());
        $this->assertInstanceOf(Shop::class, $this->shopGroup->getShops()->first());
        $this->shopGroup->addShop($shop);
    }

    public function testAddShop()
    {
        /** @var Shop|\PHPUnit_Framework_MockObject_MockObject $order */
        $shop = $this->createMock(Shop::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $orders */
        $shops = $this->createMock(Collection::class);
        $shops
            ->expects($this->once())
            ->method('contains')
            ->with($shop)
            ->willReturn(false);
        $shops
            ->expects($this->once())
            ->method('add')
            ->with($shop);
        $shop
            ->expects($this->once())
            ->method('setShopGroup');

        $ordersReflect = new \ReflectionProperty(ShopGroup::class, 'shops');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->shopGroup, $shops);

        $this->shopGroup->addShop($shop);
    }

    public function testAddShopExists()
    {
        /** @var Shop|\PHPUnit_Framework_MockObject_MockObject $order */
        $shop = $this->createMock(Shop::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $orders */
        $shops = $this->createMock(Collection::class);
        $shops
            ->expects($this->once())
            ->method('contains')
            ->with($shop)
            ->willReturn(true);
        $shops
            ->expects($this->never())
            ->method('add');
        $shop
            ->expects($this->never())
            ->method('setShopGroup');

        $ordersReflect = new \ReflectionProperty(ShopGroup::class, 'shops');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->shopGroup, $shops);

        $this->shopGroup->addShop($shop);
    }

    public function testName()
    {
        $this->assertInstanceOf(ShopGroup::class, $this->shopGroup->setName("test"));
        $this->assertInternalType('string', $this->shopGroup->getName());
        $this->assertEquals("test", $this->shopGroup->getName());
        $this->shopGroup->setName("test");
    }
}
