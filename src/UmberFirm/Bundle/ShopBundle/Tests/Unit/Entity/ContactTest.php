<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\ShopBundle\Entity\Contact;
use UmberFirm\Bundle\ShopBundle\Entity\ContactEnum;
use UmberFirm\Bundle\ShopBundle\Entity\Store;

/**
 * Class ContactTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity
 */
class ContactTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Contact
     */
    private $contact;

    /**
     *{@inheritdoc}
     */
    protected function setUp()
    {
        $this->contact = new Contact();
    }

    /**
     * @expectedException \TypeError
     */
    public function testStores()
    {
        $store = new Store();
        $this->assertInstanceOf(Contact::class, $this->contact->addStore($store));
        $this->assertInstanceOf(Collection::class, $this->contact->getStores());
        $this->assertInstanceOf(Store::class, $this->contact->getStores()->first());
        $this->assertTrue($this->contact->getStores()->contains($store));
        $this->contact->addStore(new \stdClass());
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
            ->method('addContact');

        $ordersReflect = new \ReflectionProperty(Contact::class, 'stores');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->contact, $stores);

        $this->contact->addStore($store);
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
            ->method('addContact');

        $ordersReflect = new \ReflectionProperty(Contact::class, 'stores');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->contact, $stores);

        $this->contact->addStore($store);
    }

    public function testContactEnum()
    {
        $contactEnum = new ContactEnum();
        $this->assertInstanceOf(Contact::class, $this->contact->setContactEnum($contactEnum));
        $this->assertInstanceOf(ContactEnum::class, $this->contact->getContactEnum());
        $this->contact->setContactEnum($contactEnum);
    }

    /**
     * @expectedException \TypeError
     */
    public function testContactEnumNullable()
    {
        $this->assertEquals(null, $this->contact->getContactEnum());
        $this->assertInternalType('null', $this->contact->getContactEnum());
        $this->contact->setContactEnum(new \stdClass());
    }

    public function testValue()
    {
        $this->assertInstanceOf(Contact::class, $this->contact->setValue("100"));
        $this->assertInternalType('string', $this->contact->getValue());
        $this->assertEquals("100", $this->contact->getValue());
        $this->contact->setValue("100");
    }

    public function testValueNullable()
    {
        $this->assertEquals(null, $this->contact->getValue());
        $this->assertInternalType('string', $this->contact->getValue());
        $this->contact->setValue(null);
    }
}
