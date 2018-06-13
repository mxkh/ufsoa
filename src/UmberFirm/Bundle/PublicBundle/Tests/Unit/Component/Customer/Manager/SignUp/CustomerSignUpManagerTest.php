<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Customer\Manager\SignUp;

use Doctrine\ORM\EntityManagerInterface;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerProfile;
use UmberFirm\Bundle\PublicBundle\Component\Customer\DataTransferObject\CustomerConfirmationCodeInterface;
use UmberFirm\Bundle\PublicBundle\Component\Customer\DataTransferObject\Factory\CustomerDataTransferObjectFactoryInterface;
use UmberFirm\Bundle\PublicBundle\Component\Customer\Generator\ConfirmationCodeGeneratorInterface;
use UmberFirm\Bundle\PublicBundle\Component\Customer\Manager\SignUp\CustomerSignUpManager;
use UmberFirm\Bundle\PublicBundle\Component\Customer\Producer\CustomerProducerInterface;

/**
 * Class CustomerSignUpManagerTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Customer\Manager\SignUp
 */
class CustomerSignUpManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $entityManager;

    /**
     * @var ConfirmationCodeGeneratorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $confirmationCodeGenerator;

    /**
     * @var CustomerProducerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $customerProducer;

    /**
     * @var CustomerDataTransferObjectFactoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $customerDataTransferObjectFactory;

    /**
     * @var Customer|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $customer;

    /**
     * @var CustomerConfirmationCodeInterface||\PHPUnit_Framework_MockObject_MockObject
     */
    protected $customerConfirmationCode;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->confirmationCodeGenerator = $this->createMock(ConfirmationCodeGeneratorInterface::class);
        $this->customerProducer = $this->createMock(CustomerProducerInterface::class);
        $this->customerDataTransferObjectFactory = $this->createMock(CustomerDataTransferObjectFactoryInterface::class);
        $this->customer = $this->createMock(Customer::class);
        $this->customerConfirmationCode = $this->createMock(CustomerConfirmationCodeInterface::class);
    }

    public function testComplete()
    {
        $this->customer
            ->expects($this->once())
            ->method('getIsConfirmed')
            ->willReturn(false);
        $this->customer
            ->expects($this->once())
            ->method('setIsConfirmed')
            ->with(false);

        $this->customer
            ->expects($this->once())
            ->method('getProfile')
            ->willReturn(null);
        $this->customer
            ->expects($this->once())
            ->method('setProfile')
            ->with(new CustomerProfile());

        $code = '1234';
        $this->customer
            ->expects($this->once())
            ->method('getConfirmationCode')
            ->willReturn(null);
        $this->confirmationCodeGenerator
            ->expects($this->once())
            ->method('generate')
            ->willReturn($code);
        $this->customer
            ->expects($this->once())
            ->method('setConfirmationCode')
            ->with($code);

        $this->entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($this->customer);
        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $this->customerDataTransferObjectFactory
            ->expects($this->once())
            ->method('createCustomerConfirmationCode')
            ->with($this->customer)
            ->willReturn($this->customerConfirmationCode);

        $this->customerProducer
            ->expects($this->once())
            ->method('sendConfirmationCode')
            ->with($this->customerConfirmationCode);

        $customerSignUpManager = new CustomerSignUpManager(
            $this->entityManager,
            $this->confirmationCodeGenerator,
            $this->customerProducer,
            $this->customerDataTransferObjectFactory
        );
        $customerSignUpManager->complete($this->customer);
    }

    public function testCompleteConfirmedWithProfileAndConfirmationCode()
    {
        $this->customer
            ->expects($this->once())
            ->method('getIsConfirmed')
            ->willReturn(true);
        $this->customer
            ->expects($this->never())
            ->method('setIsConfirmed');

        $this->customer
            ->expects($this->once())
            ->method('getProfile')
            ->willReturn(new CustomerProfile());
        $this->customer
            ->expects($this->never())
            ->method('setProfile');

        $code = '1234';
        $this->customer
            ->expects($this->once())
            ->method('getConfirmationCode')
            ->willReturn($code);

        $this->confirmationCodeGenerator
            ->expects($this->never())
            ->method('generate');
        $this->customer
            ->expects($this->never())
            ->method('setConfirmationCode');

        $this->entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($this->customer);
        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $this->customerDataTransferObjectFactory
            ->expects($this->once())
            ->method('createCustomerConfirmationCode')
            ->with($this->customer)
            ->willReturn($this->customerConfirmationCode);

        $this->customerProducer
            ->expects($this->once())
            ->method('sendConfirmationCode')
            ->with($this->customerConfirmationCode);

        $customerSignUpManager = new CustomerSignUpManager(
            $this->entityManager,
            $this->confirmationCodeGenerator,
            $this->customerProducer,
            $this->customerDataTransferObjectFactory
        );
        $customerSignUpManager->complete($this->customer);
    }
}
