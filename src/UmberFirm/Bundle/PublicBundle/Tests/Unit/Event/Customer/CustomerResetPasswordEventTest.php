<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Unit\Event\Customer;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\PublicBundle\Event\Customer\CustomerResetPasswordEvent;

/**
 * Class CustomerResetPasswordEventTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Unit\Event\Customer
 */
class CustomerResetPasswordEventTest extends \PHPUnit_Framework_TestCase
{
    /** @var Customer|\PHPUnit_Framework_MockObject_MockObject */
    protected $customer;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->customer = $this->createMock(Customer::class);

        parent::setUp();
    }

    public function testGetCustomerResetPassword()
    {
        $customerResetPasswordEvent = new CustomerResetPasswordEvent($this->customer);
        $customer = $customerResetPasswordEvent->getCustomer();

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals($customer, $this->customer);
    }

    /**
     * @expectedException \TypeError
     */
    public function testCustomerResetPasswordEventTypeHintingError()
    {
        $customerResetPasswordEvent = new CustomerResetPasswordEvent(null);
    }
}
