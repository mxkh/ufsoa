<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Event\Customer\Subscriber;

use UmberFirm\Bundle\PublicBundle\Component\Customer\Producer\PasswordProducerInterface;
use UmberFirm\Bundle\PublicBundle\Event\Customer\CustomerResetPasswordEvent;
use UmberFirm\Bundle\PublicBundle\Event\Customer\CustomerResetPasswordEventInterface;

/**
 * Class CustomerResetPasswordEventSubscriber
 *
 * @package UmberFirm\Bundle\CustomerBundle\Event\Subscriber
 */
class CustomerResetPasswordEventSubscriber implements CustomerResetPasswordEventSubscriberInterface
{
    /**
     * @var PasswordProducerInterface
     */
    private $passwordProducer;

    /**
     * CustomerResetPasswordEventSubscriber constructor.
     *
     * @param PasswordProducerInterface $passwordProducer
     */
    public function __construct(PasswordProducerInterface $passwordProducer)
    {
        $this->passwordProducer = $passwordProducer;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            CustomerResetPasswordEventInterface::RESET_PASSWORD => 'onResetPassword',
            CustomerResetPasswordEventInterface::CHANGE_PASSWORD => 'onChangePassword',
            CustomerResetPasswordEventInterface::CONFIRM_RESET_PASSWORD => 'onConfirmResetPassword',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function onResetPassword(CustomerResetPasswordEvent $event): void
    {
        $this->passwordProducer->sendResetPassword($event->getCustomer());
    }

    /**
     * {@inheritdoc}
     */
    public function onChangePassword(CustomerResetPasswordEvent $event): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function onConfirmResetPassword(CustomerResetPasswordEvent $event): void
    {
    }
}
