<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Tests\Unit\Entity\Customer;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerGroup;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerGroupCustomerAwareInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

class CustomerGroupTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CustomerGroup
     */
    private $group;

    protected function setUp()
    {
        $this->group = new CustomerGroup();
    }

    public function testImplementsInterfaces()
    {
        $this->assertInstanceOf(UuidEntityInterface::class, $this->group);
        $this->assertInstanceOf(CustomerGroupCustomerAwareInterface::class, $this->group);
    }

    public function testConstructorDefaults()
    {
        $this->assertInstanceOf(Collection::class, $this->group->getCustomers());
    }

    public function testAddCustomer()
    {
        /** @var Customer|\PHPUnit_Framework_MockObject_MockObject $customer */
        $customer = $this->createMock(Customer::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $customers */
        $customers = $this->createMock(Collection::class);
        $customers
            ->expects($this->once())
            ->method('contains')
            ->with($customer)
            ->willReturn(false);
        $customers
            ->expects($this->once())
            ->method('add')
            ->with($customer);
        $customer
            ->expects($this->once())
            ->method('setCustomerGroup');

        $ordersReflect = new \ReflectionProperty(CustomerGroup::class, 'customers');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->group, $customers);

        $this->group->addCustomer($customer);
    }

    public function testAddCustomerExists()
    {
        /** @var Customer|\PHPUnit_Framework_MockObject_MockObject $customer */
        $customer = $this->createMock(Customer::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $customers */
        $customers = $this->createMock(Collection::class);
        $customers
            ->expects($this->once())
            ->method('contains')
            ->with($customer)
            ->willReturn(true);
        $customers
            ->expects($this->never())
            ->method('add');
        $customer
            ->expects($this->never())
            ->method('setCustomerGroup');

        $ordersReflect = new \ReflectionProperty(CustomerGroup::class, 'customers');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->group, $customers);

        $this->group->addCustomer($customer);
    }

    /**
     * @expectedException \TypeError
     */
    public function testAddCustomerNull()
    {
        $this->group->addCustomer(null);
    }

    /**
     * @expectedException \TypeError
     */
    public function testAddCustomerTypeError()
    {
        $this->group->addCustomer(new \stdClass());
    }

    public function testRemoveCustomer()
    {
        /** @var Customer|\PHPUnit_Framework_MockObject_MockObject $customer */
        $customer = $this->createMock(Customer::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $customers */
        $customers = $this->createMock(Collection::class);
        $customers
            ->expects($this->once())
            ->method('contains')
            ->with($customer)
            ->willReturn(true);
        $customers
            ->expects($this->once())
            ->method('removeElement')
            ->with($customer);
        $customer
            ->expects($this->once())
            ->method('setCustomerGroup')
            ->with(null);

        $ordersReflect = new \ReflectionProperty(CustomerGroup::class, 'customers');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->group, $customers);

        $this->group->removeCustomer($customer);
    }

    public function testRemoveCustomerDoesNotExists()
    {
        /** @var Customer|\PHPUnit_Framework_MockObject_MockObject $customer */
        $customer = $this->createMock(Customer::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $customers */
        $customers = $this->createMock(Collection::class);
        $customers
            ->expects($this->once())
            ->method('contains')
            ->with($customer)
            ->willReturn(false);
        $customers
            ->expects($this->never())
            ->method('removeElement');
        $customer
            ->expects($this->never())
            ->method('setCustomerGroup');

        $ordersReflect = new \ReflectionProperty(CustomerGroup::class, 'customers');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->group, $customers);

        $this->group->removeCustomer($customer);
    }

    /**
     * @expectedException \TypeError
     */
    public function testRemoveCustomerNull()
    {
        $this->group->removeCustomer(null);
    }

    /**
     * @expectedException \TypeError
     */
    public function testRemoveCustomerTypeError()
    {
        $this->group->removeCustomer(new \stdClass());
    }

    public function testGetNameDefault()
    {
        $this->assertNull($this->group->getName());
    }

    public function testSetName()
    {
        $this->group->setName('group name', $this->group->getCurrentLocale());
        $this->assertEquals('group name', $this->group->getName());
    }
}
