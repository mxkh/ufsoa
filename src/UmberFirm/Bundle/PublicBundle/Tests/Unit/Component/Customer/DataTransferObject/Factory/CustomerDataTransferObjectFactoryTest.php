<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Customer\DataTransferObject\Factory;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\PublicBundle\Component\Customer\DataTransferObject\CustomerConfirmationCodeInterface;
use UmberFirm\Bundle\PublicBundle\Component\Customer\DataTransferObject\Factory\CustomerDataTransferObjectFactory;

/**
 * Class CustomerDataTransferObjectFactoryTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Customer\DataTransferObject\Factory
 */
class CustomerDataTransferObjectFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var Customer|\PHPUnit_Framework_MockObject_MockObject */
    protected $customer;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->customer = $this->createPartialMock(
            Customer::class,
            [
                'getId',
                'getPhone',
                'getConfirmationCode'
            ]
        );
    }

    public function testCreateCustomerConfirmationCode()
    {
        $factory = new CustomerDataTransferObjectFactory();
        $customerConfirmationCode = $factory->createCustomerConfirmationCode($this->customer);

        $this->assertInstanceOf(CustomerConfirmationCodeInterface::class, $customerConfirmationCode);
    }
}
