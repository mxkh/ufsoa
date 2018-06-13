<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Customer\Manager\SignUp;

use Doctrine\ORM\EntityManagerInterface;
use UmberFirm\Bundle\PublicBundle\Component\Customer\DataTransferObject\Factory\CustomerDataTransferObjectFactoryInterface;
use UmberFirm\Bundle\PublicBundle\Component\Customer\Generator\ConfirmationCodeGeneratorInterface;
use UmberFirm\Bundle\PublicBundle\Component\Customer\Producer\CustomerProducerInterface;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerProfile;

/**
 * Class CustomerSignUpManager
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Customer\Manager\SignUp
 */
class CustomerSignUpManager implements CustomerSignUpManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var ConfirmationCodeGeneratorInterface
     */
    protected $confirmationCodeGenerator;

    /**
     * @var CustomerProducerInterface
     */
    protected $customerProducer;

    /**
     * @var CustomerDataTransferObjectFactoryInterface
     */
    protected $customerDataTransferObjectFactory;

    /**
     * CustomerSignUpManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ConfirmationCodeGeneratorInterface $confirmationCodeGenerator
     * @param CustomerProducerInterface $customerProducer
     * @param CustomerDataTransferObjectFactoryInterface $customerDataTransferObjectFactory
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ConfirmationCodeGeneratorInterface $confirmationCodeGenerator,
        CustomerProducerInterface $customerProducer,
        CustomerDataTransferObjectFactoryInterface $customerDataTransferObjectFactory
    )
    {
        $this->entityManager = $entityManager;
        $this->confirmationCodeGenerator = $confirmationCodeGenerator;
        $this->customerProducer = $customerProducer;
        $this->customerDataTransferObjectFactory = $customerDataTransferObjectFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function complete(Customer $customer): void
    {
        if (true !== $customer->getIsConfirmed()) {
            $customer->setIsConfirmed(false);
        }

        if (null === $customer->getProfile()) {
            $customer->setProfile(new CustomerProfile());
        }

        if (null === $customer->getConfirmationCode()) {
            $confirmationCode = $this->confirmationCodeGenerator->generate();
            $customer->setConfirmationCode($confirmationCode);
        }

        $this->entityManager->persist($customer);
        $this->entityManager->flush();

        $this->customerProducer->sendConfirmationCode(
            $this->customerDataTransferObjectFactory->createCustomerConfirmationCode($customer)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function save(Customer $customer): Customer
    {
        $this->entityManager->persist($customer);
        $this->entityManager->flush();

        return $customer;
    }

    /**
     * {@inheritdoc}
     */
    public function addEmail(Customer $customer, string $email): Customer
    {
        $customer->setEmail($email);

        return $this->save($customer);
    }
}
