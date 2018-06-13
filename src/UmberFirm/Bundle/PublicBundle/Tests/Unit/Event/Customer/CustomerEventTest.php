<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Unit\Event\Customer;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\PublicBundle\Event\Customer\CustomerEvent;

/**
 * Class CustomerEventTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Unit\Event\Customer
 */
class CustomerEventTest extends \PHPUnit_Framework_TestCase
{
    /** @var Customer|\PHPUnit_Framework_MockObject_MockObject */
    protected $customer;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->customer = $this->createMock(Customer::class);
    }

    public function testGetCustomer()
    {
        $customerEvent = new CustomerEvent($this->customer);
        $customer = $customerEvent->getCustomer();

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals($customer, $this->customer);
    }

    /**
     * @expectedException \TypeError
     */
    public function testCustomerEventTestTypeHintingError()
    {
        $customerEvent = new CustomerEvent(null);
    }
}
