<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Tests\Unit\Entity\Customer;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerAddress;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerAddressAwareInterface;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerGroup;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerOrderAwareInterface;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerProfile;
use UmberFirm\Bundle\OrderBundle\Entity\Order;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

class CustomerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Customer
     */
    private $customer;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->customer = new Customer();
    }

    public function testImplementsInterfaces()
    {
        $this->assertInstanceOf(UuidEntityInterface::class, $this->customer);
        $this->assertInstanceOf(CustomerAddressAwareInterface::class, $this->customer);
        $this->assertInstanceOf(CustomerOrderAwareInterface::class, $this->customer);
    }

    public function testConstructorDefaults()
    {
        $this->assertInstanceOf(Collection::class, $this->customer->getOrders());
        $this->assertInstanceOf(Collection::class, $this->customer->getCustomerAddresses());
    }

    public function testGetCustomerGroupDefault()
    {
        $this->assertNull($this->customer->getCustomerGroup());
    }

    public function testSetCustomerGroup()
    {
        $this->customer->setCustomerGroup(new CustomerGroup());
        $this->assertInstanceOf(CustomerGroup::class, $this->customer->getCustomerGroup());
    }

    public function testSetCustomerGroupNull()
    {
        $this->customer->setCustomerGroup(null);
        $this->assertNull($this->customer->getCustomerGroup());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSetCustomerGroupTypeError()
    {
        $this->customer->setCustomerGroup(new \stdClass());
    }

    public function testGetShopDefault()
    {
        $this->assertNull($this->customer->getShop());
    }

    public function testSetShop()
    {
        $this->customer->setShop(new Shop());
        $this->assertInstanceOf(Shop::class, $this->customer->getShop());
    }

    public function testSetShopNull()
    {
        $this->customer->setShop(null);
        $this->assertNull($this->customer->getShop());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSetShopTypeError()
    {
        $this->customer->setShop(new \stdClass());
    }

    public function testGetEmailDefault()
    {
        $this->assertNull($this->customer->getEmail());
    }

    public function testSetEmail()
    {
        $email = 'test@mail.me';
        $this->customer->setEmail($email);
        $this->assertEquals($email, $this->customer->getEmail());
        $this->assertInternalType('string', $this->customer->getEmail());
    }

    public function testSetEmailNull()
    {
        $this->customer->setEmail(null);
        $this->assertNull($this->customer->getEmail());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSetEmailTypeError()
    {
        $this->customer->setEmail(new \stdClass());
    }

    public function testGetPhoneDefault()
    {
        $this->assertNull($this->customer->getPhone());
    }

    public function testSetPhone()
    {
        $phone = '+380501234567';
        $this->customer->setPhone($phone);
        $this->assertEquals($phone, $this->customer->getPhone());
        $this->assertInternalType('string', $this->customer->getPhone());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSetPhoneNull()
    {
        $this->customer->setPhone(new \stdClass());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSetPhoneTypeError()
    {
        $this->customer->setPhone(new \stdClass());
    }

    public function testGetPasswordDefault()
    {
        $this->assertNull($this->customer->getPassword());
    }

    public function testSetPassword()
    {
        $password = 'p@ssword';
        $this->customer->setPassword($password);
        $this->assertEquals($password, $this->customer->getPassword());
        $this->assertInternalType('string', $this->customer->getPassword());
    }

    public function testSetPasswordNull()
    {
        $this->customer->setPassword(null);
        $this->assertNull($this->customer->getPassword());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSetPasswordTypeError()
    {
        $this->customer->setPassword(new \stdClass());
    }

    public function testGetConfirmationCodeDefault()
    {
        $this->assertNull($this->customer->getConfirmationCode());
    }

    public function testSetConfirmationCode()
    {
        $confirmationCode = '1234';
        $this->customer->setConfirmationCode($confirmationCode);
        $this->assertEquals($confirmationCode, $this->customer->getConfirmationCode());
        $this->assertInternalType('string', $this->customer->getConfirmationCode());
    }

    public function testSetConfirmationCodeNull()
    {
        $this->customer->setConfirmationCode(null);
        $this->assertNull($this->customer->getConfirmationCode());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSetConfirmationCodeTypeError()
    {
        $this->customer->setConfirmationCode(new \stdClass());
    }

    public function testGetIsConfirmedDefault()
    {
        $this->assertFalse($this->customer->getIsConfirmed());
    }

    public function testSetIsConfirmed()
    {
        $isConfirmed = true;
        $this->customer->setIsConfirmed($isConfirmed);
        $this->assertTrue($this->customer->getIsConfirmed());
        $this->assertInternalType('bool', $this->customer->getIsConfirmed());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSetIsConfirmedNull()
    {
        $this->customer->setIsConfirmed(null);
    }

    /**
     * @expectedException \TypeError
     */
    public function testSetIsConfirmedTypeError()
    {
        $this->customer->setIsConfirmed(new \stdClass());
    }

    public function testGetProfileDefault()
    {
        $this->assertNull($this->customer->getProfile());
    }

    public function testSetProfile()
    {
        /** @var CustomerProfile|\PHPUnit_Framework_MockObject_MockObject $profile */
        $profile = $this->createMock(CustomerProfile::class);
        $profile
            ->expects($this->once())
            ->method('setCustomer');

        $this->customer->setProfile($profile);

        $this->assertInstanceOf(CustomerProfile::class, $this->customer->getProfile());
    }

    public function testSetProfileNull()
    {
        /** @var CustomerProfile|\PHPUnit_Framework_MockObject_MockObject $profile */
        $profile = $this->createMock(CustomerProfile::class);
        $profile
            ->expects($this->never())
            ->method('setCustomer');

        $this->customer->setProfile(null);
        $this->assertNull($this->customer->getProfile());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSetProfileTypeError()
    {
        $this->customer->setProfile(new \stdClass());
    }

    public function testAddOrder()
    {
        /** @var Order|\PHPUnit_Framework_MockObject_MockObject $order */
        $order = $this->createMock(Order::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $orders */
        $orders = $this->createMock(Collection::class);
        $orders
            ->expects($this->once())
            ->method('contains')
            ->with($order)
            ->willReturn(false);
        $orders
            ->expects($this->once())
            ->method('add')
            ->with($order);
        $order
            ->expects($this->once())
            ->method('setCustomer');

        $ordersReflect = new \ReflectionProperty(Customer::class, 'orders');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->customer, $orders);

        $this->customer->addOrder($order);
    }

    public function testAddOrderExists()
    {
        /** @var Order|\PHPUnit_Framework_MockObject_MockObject $order */
        $order = $this->createMock(Order::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $orders */
        $orders = $this->createMock(Collection::class);
        $orders
            ->expects($this->once())
            ->method('contains')
            ->with($order)
            ->willReturn(true);
        $orders
            ->expects($this->never())
            ->method('add');
        $order
            ->expects($this->never())
            ->method('setCustomer');

        $ordersReflect = new \ReflectionProperty(Customer::class, 'orders');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->customer, $orders);

        $this->customer->addOrder($order);
    }

    /**
     * @expectedException \TypeError
     */
    public function testAddOrderNull()
    {
        $this->customer->addOrder(null);
    }

    /**
     * @expectedException \TypeError
     */
    public function testAddOrderTypeError()
    {
        $this->customer->addOrder(new \stdClass());
    }

    public function testRemoveOrder()
    {
        /** @var Order|\PHPUnit_Framework_MockObject_MockObject $order */
        $order = $this->createMock(Order::class);
        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $orders */
        $orders = $this->createMock(Collection::class);
        $orders
            ->expects($this->once())
            ->method('contains')
            ->with($order)
            ->willReturn(true);
        $orders
            ->expects($this->once())
            ->method('removeElement')
            ->with($order);
        $order
            ->expects($this->once())
            ->method('setCustomer')
            ->with(null);

        $ordersReflect = new \ReflectionProperty(Customer::class, 'orders');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->customer, $orders);

        $this->customer->removeOrder($order);
    }

    public function testRemoveOrderDoesNotExists()
    {
        /** @var Order|\PHPUnit_Framework_MockObject_MockObject $order */
        $order = $this->createMock(Order::class);
        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $orders */
        $orders = $this->createMock(Collection::class);
        $orders
            ->expects($this->once())
            ->method('contains')
            ->with($order)
            ->willReturn(false);
        $orders
            ->expects($this->never())
            ->method('removeElement');
        $order
            ->expects($this->never())
            ->method('setCustomer');

        $ordersReflect = new \ReflectionProperty(Customer::class, 'orders');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->customer, $orders);

        $this->customer->removeOrder($order);
    }

    /**
     * @expectedException \TypeError
     */
    public function testRemoveOrderNull()
    {
        $this->customer->removeOrder(null);
    }

    /**
     * @expectedException \TypeError
     */
    public function testRemoveOrderTypeError()
    {
        $this->customer->removeOrder(new \stdClass());
    }

    public function testAddCustomerAddress()
    {
        /** @var CustomerAddress|\PHPUnit_Framework_MockObject_MockObject $customerAddress */
        $customerAddress = $this->createMock(CustomerAddress::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $customerAddresses */
        $customerAddresses = $this->createMock(Collection::class);
        $customerAddresses
            ->expects($this->once())
            ->method('contains')
            ->with($customerAddress)
            ->willReturn(false);
        $customerAddresses
            ->expects($this->once())
            ->method('add')
            ->with($customerAddress);
        $customerAddress
            ->expects($this->once())
            ->method('setCustomer');

        $customerAddressesReflect = new \ReflectionProperty(Customer::class, 'customerAddresses');
        $customerAddressesReflect->setAccessible(true);
        $customerAddressesReflect->setValue($this->customer, $customerAddresses);

        $this->customer->addCustomerAddress($customerAddress);
    }

    public function testAddCustomerAddressExists()
    {
        /** @var CustomerAddress|\PHPUnit_Framework_MockObject_MockObject $customerAddress */
        $customerAddress = $this->createMock(CustomerAddress::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $customerAddresses */
        $customerAddresses = $this->createMock(Collection::class);
        $customerAddresses
            ->expects($this->once())
            ->method('contains')
            ->with($customerAddress)
            ->willReturn(true);
        $customerAddresses
            ->expects($this->never())
            ->method('add');
        $customerAddress
            ->expects($this->never())
            ->method('setCustomer');

        $customerAddressesReflect = new \ReflectionProperty(Customer::class, 'customerAddresses');
        $customerAddressesReflect->setAccessible(true);
        $customerAddressesReflect->setValue($this->customer, $customerAddresses);

        $this->customer->addCustomerAddress($customerAddress);
    }

    /**
     * @expectedException \TypeError
     */
    public function testAddCustomerAddressNull()
    {
        $this->customer->addCustomerAddress(null);
    }

    /**
     * @expectedException \TypeError
     */
    public function testAddCustomerAddressTypeError()
    {
        $this->customer->addCustomerAddress(new \stdClass());
    }

    public function testRemoveCustomerAddress()
    {
        /** @var CustomerAddress|\PHPUnit_Framework_MockObject_MockObject $customerAddress */
        $customerAddress = $this->createMock(CustomerAddress::class);
        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $customerAddresses */
        $customerAddresses = $this->createMock(Collection::class);
        $customerAddresses
            ->expects($this->once())
            ->method('contains')
            ->with($customerAddress)
            ->willReturn(true);
        $customerAddresses
            ->expects($this->once())
            ->method('removeElement')
            ->with($customerAddress);
        $customerAddress
            ->expects($this->once())
            ->method('setCustomer')
            ->with(null);

        $customerAddressesReflect = new \ReflectionProperty(Customer::class, 'customerAddresses');
        $customerAddressesReflect->setAccessible(true);
        $customerAddressesReflect->setValue($this->customer, $customerAddresses);

        $this->customer->removeCustomerAddress($customerAddress);
    }

    public function testRemoveCustomerAddressDoesNotExists()
    {
        /** @var CustomerAddress|\PHPUnit_Framework_MockObject_MockObject $customerAddress */
        $customerAddress = $this->createMock(CustomerAddress::class);
        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $customerAddresses */
        $customerAddresses = $this->createMock(Collection::class);
        $customerAddresses
            ->expects($this->once())
            ->method('contains')
            ->with($customerAddress)
            ->willReturn(false);
        $customerAddresses
            ->expects($this->never())
            ->method('removeElement');
        $customerAddress
            ->expects($this->never())
            ->method('setCustomer');

        $customerAddressesReflect = new \ReflectionProperty(Customer::class, 'customerAddresses');
        $customerAddressesReflect->setAccessible(true);
        $customerAddressesReflect->setValue($this->customer, $customerAddresses);

        $this->customer->removeCustomerAddress($customerAddress);
    }

    /**
     * @expectedException \TypeError
     */
    public function testRemoveCustomerAddressNull()
    {
        $this->customer->removeCustomerAddress(null);
    }

    /**
     * @expectedException \TypeError
     */
    public function testRemoveCustomerAddressTypeError()
    {
        $this->customer->removeCustomerAddress(new \stdClass());
    }    
}
