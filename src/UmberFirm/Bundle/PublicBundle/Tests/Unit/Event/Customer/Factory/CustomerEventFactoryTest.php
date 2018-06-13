<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Unit\Event\Customer\Factory;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\PublicBundle\Event\Customer\CustomerEventInterface;
use UmberFirm\Bundle\PublicBundle\Event\Customer\Factory\CustomerEventFactory;

/**
 * Class CustomerEventFactoryTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Unit\Event\Customer\Factory
 */
class CustomerEventFactoryTest extends \PHPUnit_Framework_TestCase
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

    public function testCreateCustomerEvent()
    {
        $customerEventFactory = new CustomerEventFactory();
        $customerEvent = $customerEventFactory->createCustomerEvent($this->customer);

        $this->assertInstanceOf(CustomerEventInterface::class, $customerEvent);
    }

    /**
     * @expectedException \TypeError
     */
    public function testCustomerEventFactoryTypeHintingError()
    {
        $customerEventFactory = new CustomerEventFactory();
        $customerEventFactory->createCustomerEvent(null);
    }
}
