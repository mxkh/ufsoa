<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Tests\Unit\Entity;

use DateTime;
use UmberFirm\Bundle\CommonBundle\Entity\Gender;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerProfile;

/**
 * Class CustomerProfileTest
 *
 * @package UmberFirm\Bundle\CustomerBundle\Tests\Unit
 */
class CustomerProfileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CustomerProfile
     */
    private $customerProfile;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->customerProfile = new CustomerProfile();
    }

    public function testDefaultCustomer()
    {
        $this->assertInstanceOf(CustomerProfile::class, $this->customerProfile->setCustomer(null));
        $this->assertEquals(null, $this->customerProfile->getCustomer());
    }

    public function testCustomer()
    {
        $this->assertInstanceOf(CustomerProfile::class, $this->customerProfile->setCustomer(new Customer));
        $this->assertInstanceOf(Customer::class, $this->customerProfile->getCustomer());
    }

    public function testFirstName()
    {
        $this->assertInstanceOf(CustomerProfile::class, $this->customerProfile->setFirstname('John'));
        $this->assertEquals('John', $this->customerProfile->getFirstname());
        $this->assertInternalType('string', $this->customerProfile->getFirstname());
    }

    public function testLastName()
    {
        $this->assertInstanceOf(CustomerProfile::class, $this->customerProfile->setLastname('Doe'));
        $this->assertEquals('Doe', $this->customerProfile->getLastname());
        $this->assertInternalType('string', $this->customerProfile->getLastname());
    }

    public function testBirthday()
    {
        $this->assertInstanceOf(CustomerProfile::class, $this->customerProfile->setBirthday(new DateTime));
        $this->assertInstanceOf(DateTime::class, $this->customerProfile->getBirthday());
    }

    public function testDefaultGender()
    {
        $this->assertInstanceOf(CustomerProfile::class, $this->customerProfile->setGender(null));
        $this->assertEquals(null, $this->customerProfile->getGender());
    }

    public function testGender()
    {
        $this->assertInstanceOf(CustomerProfile::class, $this->customerProfile->setGender(new Gender));
        $this->assertInstanceOf(Gender::class, $this->customerProfile->getGender());
    }
}
