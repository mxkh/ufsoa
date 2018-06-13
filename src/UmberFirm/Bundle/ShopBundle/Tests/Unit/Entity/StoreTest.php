<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\ShopBundle\Entity\Contact;
use UmberFirm\Bundle\ShopBundle\Entity\Geolocation;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\Store;
use UmberFirm\Bundle\ShopBundle\Entity\StoreEnum;
use UmberFirm\Bundle\ShopBundle\Entity\StoreSocialProfile;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;

/**
 * Class StoreTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity
 */
class StoreTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Store
     */
    private $store;

    /** @var string */
    private $locale;

    /**
     *{@inheritdoc}
     */
    protected function setUp()
    {
        $this->store = new Store();
        $this->locale = $this->store->getCurrentLocale();
    }

    public function testName()
    {
        $this->assertInstanceOf(Store::class, $this->store->setName("100"));
        $this->assertInternalType('string', $this->store->getName());
        $this->assertEquals("100", $this->store->getName());
        $this->store->setName("100");
    }

    /**
     * @expectedException \TypeError
     */
    public function testWrongTypeName()
    {
        $this->store->setName(123);
    }

    public function testGetStoreSocialProfiles()
    {
        $this->assertInstanceOf(Collection::class, $this->store->GetStoreSocialProfiles());
    }

    public function testAddress()
    {
        $this->assertInstanceOf(Store::class, $this->store->setAddress("100", $this->locale));
        $this->assertInternalType('string', $this->store->getAddress());
        $this->assertEquals("100", $this->store->getAddress());
        $this->store->setAddress("100", $this->locale);
    }

    /**
     * @expectedException \TypeError
     */
    public function testWrongTypeAddress()
    {
        $this->store->setAddress(123, $this->locale);
    }

    /**
     * @expectedException \TypeError
     */
    public function testWrongTypeAddressLocale()
    {
        $this->store->setAddress("123", 123);
    }

    public function testDescription()
    {
        $this->assertInstanceOf(Store::class, $this->store->setDescription("100", $this->locale));
        $this->assertInternalType('string', $this->store->getDescription());
        $this->assertEquals("100", $this->store->getDescription());
        $this->store->setDescription("100", $this->locale);
    }

    /**
     * @expectedException \TypeError
     */
    public function testWrongTypeDescription()
    {
        $this->store->setDescription(123, $this->locale);
    }

    public function testSchedule()
    {
        $this->assertInstanceOf(Store::class, $this->store->setSchedule("100", $this->locale));
        $this->assertInternalType('string', $this->store->getSchedule());
        $this->assertEquals("100", $this->store->getSchedule());
        $this->store->setSchedule("100", $this->locale);
    }

    /**
     * @expectedException \TypeError
     */
    public function testWrongTypeSchedule()
    {
        $this->store->setSchedule(123, $this->locale);
    }

    /**
     * @expectedException \TypeError
     */
    public function testWrongTypeScheduleLocale()
    {
        $this->store->setSchedule("123", 123);
    }

    public function testIsActive()
    {
        $this->assertInstanceOf(Store::class, $this->store->setIsActive(true));
        $this->assertInternalType('bool', $this->store->getIsActive());
        $this->assertEquals(true, $this->store->getIsActive());
        $this->store->setIsActive(false);
    }

    /**
     * @expectedException \TypeError
     */
    public function testWrongTypeIsActive()
    {
        $this->store->setIsActive("123");
    }

    public function testStoreEnum()
    {
        $storeEnum = new StoreEnum();
        $this->assertInstanceOf(Store::class, $this->store->setStoreEnum($storeEnum));
        $this->assertInstanceOf(StoreEnum::class, $this->store->getStoreEnum());
        $this->assertEquals($storeEnum, $this->store->getStoreEnum());
        $this->store->setStoreEnum($storeEnum);
    }

    public function testSetStoreEnum()
    {
        /** @var StoreEnum|\PHPUnit_Framework_MockObject_MockObject $order */
        $storeEnum = $this->createMock(StoreEnum::class);
        $storeEnum
            ->expects($this->once())
            ->method('addStore');

        $ordersReflect = new \ReflectionProperty(Store::class, 'storeEnum');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->store, $storeEnum);

        $this->store->setStoreEnum($storeEnum);
    }

    public function testStoreSocialProfiles()
    {
        $storeSocialProfiles = new StoreSocialProfile();
        $this->assertInstanceOf(Store::class, $this->store->addStoreSocialProfile($storeSocialProfiles));
        $this->assertInstanceOf(Collection::class, $this->store->getStoreSocialProfiles());
        $this->assertEquals($storeSocialProfiles, $this->store->getStoreSocialProfiles()->first());
        $this->store->addStoreSocialProfile($storeSocialProfiles);
    }

    public function testAddStoreSocialProfile()
    {
        /** @var StoreSocialProfile|\PHPUnit_Framework_MockObject_MockObject $order */
        $storeSocialProfile = $this->createMock(StoreSocialProfile::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $orders */
        $storeSocialProfiles = $this->createMock(Collection::class);
        $storeSocialProfiles
            ->expects($this->once())
            ->method('contains')
            ->with($storeSocialProfile)
            ->willReturn(false);
        $storeSocialProfiles
            ->expects($this->once())
            ->method('add')
            ->with($storeSocialProfile);
        $storeSocialProfile
            ->expects($this->once())
            ->method('addStore');

        $ordersReflect = new \ReflectionProperty(Store::class, 'storeSocialProfiles');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->store, $storeSocialProfiles);

        $this->store->addStoreSocialProfile($storeSocialProfile);
    }

    public function testAddStoreSocialProfileExists()
    {
        /** @var StoreSocialProfile|\PHPUnit_Framework_MockObject_MockObject $order */
        $storeSocialProfile = $this->createMock(StoreSocialProfile::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $orders */
        $storeSocialProfiles = $this->createMock(Collection::class);
        $storeSocialProfiles
            ->expects($this->once())
            ->method('contains')
            ->with($storeSocialProfile)
            ->willReturn(true);
        $storeSocialProfiles
            ->expects($this->never())
            ->method('add');
        $storeSocialProfile
            ->expects($this->never())
            ->method('addStore');

        $ordersReflect = new \ReflectionProperty(Store::class, 'storeSocialProfiles');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->store, $storeSocialProfiles);

        $this->store->addStoreSocialProfile($storeSocialProfile);
    }

    public function testRemoveStoreSocialProfile()
    {
        /** @var StoreSocialProfile|\PHPUnit_Framework_MockObject_MockObject $order */
        $storeSocialProfile = $this->createMock(StoreSocialProfile::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $orders */
        $storeSocialProfiles = $this->createMock(Collection::class);
        $storeSocialProfiles
            ->expects($this->once())
            ->method('contains')
            ->with($storeSocialProfile)
            ->willReturn(true);
        $storeSocialProfiles
            ->expects($this->once())
            ->method('removeElement')
            ->with($storeSocialProfile);
        $storeSocialProfile
            ->expects($this->once())
            ->method('removeStore');

        $ordersReflect = new \ReflectionProperty(Store::class, 'storeSocialProfiles');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->store, $storeSocialProfiles);

        $this->store->removeStoreSocialProfile($storeSocialProfile);
    }

    public function testSupplier()
    {
        $supplier = new Supplier();
        $this->assertInstanceOf(Store::class, $this->store->setSupplier($supplier));
        $this->assertInstanceOf(Supplier::class, $this->store->getSupplier());
        $this->store->setSupplier($supplier);
    }

    public function testAddSupplier()
    {
        /** @var Supplier|\PHPUnit_Framework_MockObject_MockObject $order */
        $supplier = $this->createMock(Supplier::class);
        $supplier
            ->expects($this->once())
            ->method('addStore');

        $ordersReflect = new \ReflectionProperty(Store::class, 'supplier');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->store, $supplier);

        $this->store->setSupplier($supplier);
    }

    public function testGeolocation()
    {
        $supplier = new Geolocation();
        $this->assertInstanceOf(Store::class, $this->store->setGeolocation($supplier));
        $this->assertInstanceOf(Geolocation::class, $this->store->getGeolocation());
        $this->store->setGeolocation($supplier);
    }

    public function testAddGeolocation()
    {
        /** @var Geolocation|\PHPUnit_Framework_MockObject_MockObject $order */
        $geolocation = $this->createMock(Geolocation::class);
        $geolocation
            ->expects($this->once())
            ->method('addStore');

        $ordersReflect = new \ReflectionProperty(Store::class, 'geolocation');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->store, $geolocation);

        $this->store->setGeolocation($geolocation);
    }

    public function testContacts()
    {
        $contact = new Contact();
        $this->assertInstanceOf(Store::class, $this->store->addContact($contact));
        $this->assertInstanceOf(Collection::class, $this->store->getContacts());
        $this->assertInstanceOf(Contact::class, $this->store->getContacts()->first());
        $this->assertEquals($contact, $this->store->getContacts()->first());
        $this->store->addContact($contact);
    }

    public function testAddContact()
    {
        /** @var Contact|\PHPUnit_Framework_MockObject_MockObject $order */
        $contact = $this->createMock(Contact::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $orders */
        $contacts = $this->createMock(Collection::class);
        $contacts
            ->expects($this->once())
            ->method('contains')
            ->with($contact)
            ->willReturn(false);
        $contacts
            ->expects($this->once())
            ->method('add')
            ->with($contact);
        $contact
            ->expects($this->once())
            ->method('addStore');

        $ordersReflect = new \ReflectionProperty(Store::class, 'contacts');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->store, $contacts);

        $this->store->addContact($contact);
    }

    public function testAddContactExists()
    {
        /** @var StoreSocialProfile|\PHPUnit_Framework_MockObject_MockObject $order */
        $contact = $this->createMock(Contact::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $orders */
        $contacts = $this->createMock(Collection::class);
        $contacts
            ->expects($this->once())
            ->method('contains')
            ->with($contact)
            ->willReturn(true);
        $contacts
            ->expects($this->never())
            ->method('add');
        $contact
            ->expects($this->never())
            ->method('addStore');

        $ordersReflect = new \ReflectionProperty(Store::class, 'contacts');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->store, $contacts);

        $this->store->addContact($contact);
    }

    public function testRemoveContact()
    {
        /** @var Contact|\PHPUnit_Framework_MockObject_MockObject $order */
        $contact = $this->createMock(Contact::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $orders */
        $contacts = $this->createMock(Collection::class);
        $contacts
            ->expects($this->once())
            ->method('contains')
            ->with($contact)
            ->willReturn(true);
        $contacts
            ->expects($this->once())
            ->method('removeElement')
            ->with($contact);

        $ordersReflect = new \ReflectionProperty(Store::class, 'contacts');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->store, $contacts);

        $this->store->removeContact($contact);
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
            ->method('addStore');

        $ordersReflect = new \ReflectionProperty(Store::class, 'shops');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->store, $shops);

        $this->store->addShop($shop);
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
            ->method('addStore');

        $ordersReflect = new \ReflectionProperty(Store::class, 'shops');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->store, $shops);

        $this->store->addShop($shop);
    }

    public function testRemoveShop()
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
            ->expects($this->once())
            ->method('removeElement')
            ->with($shop);

        $ordersReflect = new \ReflectionProperty(Store::class, 'shops');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->store, $shops);

        $this->store->removeShop($shop);
    }

    public function testSlug()
    {
        $this->assertInstanceOf(Store::class, $this->store->setSlug('12312312'));
        $this->assertEquals('12312312', $this->store->getSlug());
    }
}
