<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\ShopBundle\Entity\Geolocation;
use UmberFirm\Bundle\ShopBundle\Entity\Store;

/**
 * Class GeolocationTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity
 */
class GeolocationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Geolocation
     */
    private $geolocation;

    /**
     *{@inheritdoc}
     */
    protected function setUp()
    {
        $this->geolocation = new Geolocation();
    }

    public function testLatitude()
    {
        $this->assertInstanceOf(Geolocation::class, $this->geolocation->setLatitude(12.12312313));
        $this->assertInternalType('float', $this->geolocation->getLatitude());
        $this->assertEquals(12.12312313, $this->geolocation->getLatitude());
        $this->geolocation->setLatitude(12.12312313);
    }

    /**
     * @expectedException \TypeError
     */
    public function testWrongTypeLatitude()
    {
        $this->geolocation->setLatitude("12.12312313");
    }

    public function testNullTypeLatitude()
    {
        $this->geolocation->getLatitude();
    }

    public function testLongitude()
    {
        $this->assertInstanceOf(Geolocation::class, $this->geolocation->setLongitude(12.12312313));
        $this->assertInternalType('float', $this->geolocation->getLongitude());
        $this->assertEquals(12.12312313, $this->geolocation->getLongitude());
        $this->geolocation->setLongitude(12.12312313);
    }

    /**
     * @expectedException \TypeError
     */
    public function testWrongTypeLongitude()
    {
        $this->geolocation->setLongitude("12.12312313");
    }

    public function testStores()
    {
        $store = new Store();
        $this->assertInstanceOf(Geolocation::class, $this->geolocation->addStore($store));
        $this->assertInstanceOf(Collection::class, $this->geolocation->getStores());
        $this->assertEquals($store, $this->geolocation->getStores()->first());
        $this->geolocation->addStore($store);
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
            ->method('setGeolocation');

        $ordersReflect = new \ReflectionProperty(Geolocation::class, 'stores');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->geolocation, $stores);

        $this->geolocation->addStore($store);
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
            ->method('setGeolocation');

        $ordersReflect = new \ReflectionProperty(Geolocation::class, 'stores');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->geolocation, $stores);

        $this->geolocation->addStore($store);
    }
}
