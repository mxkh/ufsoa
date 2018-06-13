<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Tests\Unit\Entity\Customer;

use UmberFirm\Bundle\CommonBundle\Entity\Branch;
use UmberFirm\Bundle\CommonBundle\Entity\City;
use UmberFirm\Bundle\CommonBundle\Entity\Street;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerAddress;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDelivery;

/**
 * Class CustomerAddressTest
 *
 * @package UmberFirm\Bundle\CustomerBundle\Tests\Unit\Entity\Customer
 */
class CustomerAddressTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CustomerAddress
     */
    private $address;

    protected function setUp()
    {
        $this->address = new CustomerAddress();
    }

    /**
     * @expectedException \TypeError
     */
    public function testFirstnameTypeError()
    {
        $this->address->setFirstname(123);
    }

    public function testFirstname()
    {
        $this->assertNull($this->address->getFirstname());
        $this->assertInstanceOf(CustomerAddress::class, $this->address->setFirstname('John'));
        $this->assertEquals('John', $this->address->getFirstname());
        $this->assertInternalType('string', $this->address->getFirstname());
    }

    /**
     * @expectedException \TypeError
     */
    public function testLastnameTypeError()
    {
        $this->address->setLastname(123);
    }

    public function testLastname()
    {
        $this->assertNull($this->address->getLastname());
        $this->assertInstanceOf(CustomerAddress::class, $this->address->setLastname('John'));
        $this->assertEquals('John', $this->address->getLastname());
        $this->assertInternalType('string', $this->address->getLastname());
    }

    /**
     * @expectedException \TypeError
     */
    public function testPhoneTypeError()
    {
        $this->address->setPhone(123);
    }

    public function testPhone()
    {
        $this->assertNull($this->address->getPhone());
        $this->assertInstanceOf(CustomerAddress::class, $this->address->setPhone('John'));
        $this->assertEquals('John', $this->address->getPhone());
        $this->assertInternalType('string', $this->address->getPhone());
    }

    /**
     * @expectedException \TypeError
     */
    public function testCityTypeError()
    {
        $this->address->setCity(new \stdClass());
    }

    public function testCity()
    {
        $this->assertNull($this->address->getCity());
        $this->assertInstanceOf(CustomerAddress::class, $this->address->setCity(new City()));
        $this->assertInstanceOf(City::class, $this->address->getCity());
    }

    /**
     * @expectedException \TypeError
     */
    public function testZipTypeError()
    {
        $this->address->setZip(123);
    }

    public function testZip()
    {
        $this->assertNull($this->address->getZip());
        $this->assertInstanceOf(CustomerAddress::class, $this->address->setZip('1234'));
        $this->assertEquals('1234', $this->address->getZip());
        $this->assertInternalType('string', $this->address->getZip());
    }

    /**
     * @expectedException \TypeError
     */
    public function testCountryTypeError()
    {
        $this->address->setCountry(123);
    }

    public function testCountry()
    {
        $this->assertNull($this->address->getCountry());
        $this->assertInstanceOf(CustomerAddress::class, $this->address->setCountry('1234'));
        $this->assertEquals('1234', $this->address->getCountry());
        $this->assertInternalType('string', $this->address->getCountry());
    }

    /**
     * @expectedException \TypeError
     */
    public function testCustomerTypeError()
    {
        $this->address->setCountry(new \stdClass());
    }

    public function testCustomer()
    {
        $this->assertNull($this->address->getCustomer());
        $this->assertInstanceOf(CustomerAddress::class, $this->address->setCustomer(new Customer()));
        $this->assertInstanceOf(Customer::class, $this->address->getCustomer());
    }

    /**
     * @expectedException \TypeError
     */
    public function testDeliveryTypeError()
    {
        $this->address->setDelivery(new \stdClass());
    }

    public function testDelivery()
    {
        $this->assertNull($this->address->getDelivery());
        $this->assertInstanceOf(CustomerAddress::class, $this->address->setDelivery(new ShopDelivery()));
        $this->assertInstanceOf(ShopDelivery::class, $this->address->getDelivery());
    }

    /**
     * @expectedException \TypeError
     */
    public function testStreetTypeError()
    {
        $this->address->setStreet(new \stdClass());
    }

    public function testStreet()
    {
        $this->assertNull($this->address->getStreet());
        $this->assertInstanceOf(CustomerAddress::class, $this->address->setStreet(new Street()));
        $this->assertInstanceOf(Street::class, $this->address->getStreet());
    }

    /**
     * @expectedException \TypeError
     */
    public function testBranchTypeError()
    {
        $this->address->setBranch(new \stdClass());
    }

    public function testBranch()
    {
        $this->assertNull($this->address->getBranch());
        $this->assertInstanceOf(CustomerAddress::class, $this->address->setBranch(new Branch()));
        $this->assertInstanceOf(Branch::class, $this->address->getBranch());
    }

    /**
     * @expectedException \TypeError
     */
    public function testApartmentTypeError()
    {
        $this->address->setApartment(123);
    }

    public function testApartment()
    {
        $this->assertNull($this->address->getApartment());
        $this->assertInstanceOf(CustomerAddress::class, $this->address->setApartment('1234'));
        $this->assertEquals('1234', $this->address->getApartment());
        $this->assertInternalType('string', $this->address->getApartment());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShopTypeError()
    {
        $this->address->setShop(new \stdClass());
    }

    public function testShop()
    {
        $this->assertNull($this->address->getShop());
        $this->assertInstanceOf(CustomerAddress::class, $this->address->setShop(new Shop()));
        $this->assertInstanceOf(Shop::class, $this->address->getShop());
    }
}
