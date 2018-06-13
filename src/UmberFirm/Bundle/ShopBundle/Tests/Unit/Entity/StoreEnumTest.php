<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\ShopBundle\Entity\Store;
use UmberFirm\Bundle\ShopBundle\Entity\StoreEnum;

/**
 * Class StoreEnumTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity
 */
class StoreEnumTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var StoreEnum
     */
    private $storeEnum;

    /** @var string */
    private $locale;

    /**
     *{@inheritdoc}
     */
    protected function setUp()
    {
        $this->storeEnum = new StoreEnum();
        $this->locale = $this->storeEnum->getCurrentLocale();
    }

    public function testStores()
    {
        $store = new Store();
        $this->assertInstanceOf(StoreEnum::class, $this->storeEnum->addStore($store));
        $this->assertInstanceOf(Collection::class, $this->storeEnum->getStores());
        $this->storeEnum->addStore($store);
    }

    public function testName()
    {
        $this->assertInstanceOf(StoreEnum::class, $this->storeEnum->setName("100", $this->locale));
        $this->assertInternalType('string', $this->storeEnum->getName());
        $this->assertEquals("100", $this->storeEnum->getName());
        $this->storeEnum->setName("100", $this->locale);
    }

    /**
     * @expectedException \TypeError
     */
    public function testWrongName()
    {
        $this->storeEnum->setName(123, $this->locale);
    }

    /**
     * @expectedException \TypeError
     */
    public function testWrongNameLocale()
    {
        $this->storeEnum->setName("123", 123);
    }

    public function testAddStore()
    {
        /** @var Store|\PHPUnit_Framework_MockObject_MockObject $order */
        $store = $this->createMock(Store::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $orders */
        $stores = $this->createMock(Collection::class);
        $stores
            ->expects($this->once())
            ->method('contains')
            ->with($store)
            ->willReturn(false);
        $stores
            ->expects($this->once())
            ->method('add')
            ->with($store);
        $store
            ->expects($this->once())
            ->method('setStoreEnum');

        $ordersReflect = new \ReflectionProperty(StoreEnum::class, 'stores');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->storeEnum, $stores);

        $this->storeEnum->addStore($store);
    }

    public function testAddStoreExists()
    {
        /** @var Store|\PHPUnit_Framework_MockObject_MockObject $order */
        $store = $this->createMock(Store::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $orders */
        $stores = $this->createMock(Collection::class);
        $stores
            ->expects($this->once())
            ->method('contains')
            ->with($store)
            ->willReturn(true);
        $stores
            ->expects($this->never())
            ->method('add');
        $store
            ->expects($this->never())
            ->method('setStoreEnum');

        $ordersReflect = new \ReflectionProperty(StoreEnum::class, 'stores');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->storeEnum, $stores);

        $this->storeEnum->addStore($store);
    }
}
