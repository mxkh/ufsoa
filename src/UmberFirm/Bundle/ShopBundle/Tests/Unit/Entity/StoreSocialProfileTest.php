<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\ShopBundle\Entity\Store;
use UmberFirm\Bundle\ShopBundle\Entity\StoreSocialProfile;

/**
 * Class StoreSocialProfileTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity
 */
class StoreSocialProfileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var StoreSocialProfile
     */
    private $storeSocialProfile;

    /**
     *{@inheritdoc}
     */
    protected function setUp()
    {
        $this->storeSocialProfile = new StoreSocialProfile();
    }

    public function testStores()
    {
        $store = new Store();
        $this->assertInstanceOf(StoreSocialProfile::class, $this->storeSocialProfile->addStore($store));
        $this->assertInstanceOf(Collection::class, $this->storeSocialProfile->getStores());
        $this->storeSocialProfile->addStore($store);
    }

    public function testValue()
    {
        $this->assertInstanceOf(StoreSocialProfile::class, $this->storeSocialProfile->setValue("100"));
        $this->assertInternalType('string', $this->storeSocialProfile->getValue());
        $this->assertEquals("100", $this->storeSocialProfile->getValue());
        $this->storeSocialProfile->setValue("100");
    }

    /**
     * @expectedException \TypeError
     */
    public function testWrongValue()
    {
        $this->storeSocialProfile->setValue(123);
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
            ->method('addStoreSocialProfile');

        $ordersReflect = new \ReflectionProperty(StoreSocialProfile::class, 'stores');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->storeSocialProfile, $stores);

        $this->storeSocialProfile->addStore($store);
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
            ->method('addStoreSocialProfile');

        $ordersReflect = new \ReflectionProperty(StoreSocialProfile::class, 'stores');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->storeSocialProfile, $stores);

        $this->storeSocialProfile->addStore($store);
    }

    public function testRemoveStore()
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
            ->method('removeElement')
            ->with($store);
        $store
            ->expects($this->once())
            ->method('removeStoreSocialProfile');

        $ordersReflect = new \ReflectionProperty(StoreSocialProfile::class, 'stores');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->storeSocialProfile, $stores);

        $this->storeSocialProfile->removeStore($store);
    }
}
