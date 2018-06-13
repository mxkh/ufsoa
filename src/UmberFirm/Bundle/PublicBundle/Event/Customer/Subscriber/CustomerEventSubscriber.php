<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Event\Customer\Subscriber;

use UmberFirm\Bundle\PublicBundle\Component\Customer\Manager\SignUp\CustomerSignUpManagerInterface;
use UmberFirm\Bundle\PublicBundle\Event\Customer\CustomerEventInterface;

/**
 * Class CustomerEventSubscriber
 *
 * @package UmberFirm\Bundle\CustomerBundle\Event\Subscriber
 */
class CustomerEventSubscriber implements CustomerEventSubscriberInterface
{
    /**
     * @var CustomerSignUpManagerInterface
     */
    protected $customerSignUpManager;

    /**
     * CustomerEventSubscriber constructor.
     *
     * @param CustomerSignUpManagerInterface $customerSignUpManager
     */
    public function __construct(CustomerSignUpManagerInterface $customerSignUpManager)
    {
        $this->customerSignUpManager = $customerSignUpManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            CustomerEventInterface::SIGN_UP => 'onCustomerSignUp'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function onCustomerSignUp(CustomerEventInterface $event): void
    {
        $customer = $event->getCustomer();
        $this->customerSignUpManager->complete($customer);
    }

    /**
     * {@inheritdoc}
     */
    public function onCustomerConfirm(CustomerEventInterface $event): void
    {
        //TODO: some action
    }
}
