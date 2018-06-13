<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Unit\Event\Customer\Subscriber;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\PublicBundle\Component\Customer\Manager\SignUp\CustomerSignUpManagerInterface;
use UmberFirm\Bundle\PublicBundle\Event\Customer\CustomerEventInterface;
use UmberFirm\Bundle\PublicBundle\Event\Customer\Subscriber\CustomerEventSubscriber;


/**
 * Class CustomerEventSubscriberTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Unit\Event\Customer\Subscriber
 */
class CustomerEventSubscriberTest extends \PHPUnit_Framework_TestCase
{
    /** @var Customer|\PHPUnit_Framework_MockObject_MockObject */
    protected $customer;

    /** @var CustomerEventInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $customerEvent;

    /** @var CustomerSignUpManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $customerSignUpManager;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->customer = $this->createMock(Customer::class);
        $this->customerEvent = $this->createMock(CustomerEventInterface::class);
        $this->customerSignUpManager = $this->createMock(CustomerSignUpManagerInterface::class);
    }

    public function testGetSubscribedEvents()
    {
        $subscribedEvents = CustomerEventSubscriber::getSubscribedEvents();

        $this->assertInternalType('array', $subscribedEvents);
        $this->assertEquals([CustomerEventInterface::SIGN_UP => 'onCustomerSignUp',], $subscribedEvents);
    }

    public function testOnCustomerSignUp()
    {
        $this->customerEvent
            ->expects($this->once())
            ->method('getCustomer')
            ->willReturn($this->customer);

        $this->customerSignUpManager
            ->expects($this->once())
            ->method('complete')
            ->with($this->customer);

        $customerEventSubscriber = new CustomerEventSubscriber($this->customerSignUpManager);
        $customerEventSubscriber->onCustomerSignUp($this->customerEvent);
    }
}
