<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Unit\Event\Customer\Factory;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\PublicBundle\Event\Customer\CustomerResetPasswordEventInterface;
use UmberFirm\Bundle\PublicBundle\Event\Customer\Factory\CustomerResetPasswordEventFactory;

/**
 * Class CustomerResetPasswordEventFactoryTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Unit\Event\Customer\Factory
 */
class CustomerResetPasswordEventFactoryTest extends \PHPUnit_Framework_TestCase
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

    public function testCreateCustomerResetPasswordEvent()
    {
        $customerResetPasswordEventFactory = new CustomerResetPasswordEventFactory();
        $customerResetPasswordEvent = $customerResetPasswordEventFactory->createCustomerResetPasswordEvent($this->customer);

        $this->assertInstanceOf(CustomerResetPasswordEventInterface::class, $customerResetPasswordEvent);
    }

    /**
     * @expectedException \TypeError
     */
    public function testCustomerResetPasswordEventFactoryTypeHintingError()
    {
        $customerResetPasswordEventFactory = new CustomerResetPasswordEventFactory();
        $customerResetPasswordEventFactory->createCustomerResetPasswordEvent(null);
    }
}
