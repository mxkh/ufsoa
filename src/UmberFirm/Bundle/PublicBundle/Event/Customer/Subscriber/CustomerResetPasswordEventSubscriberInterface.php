<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Event\Customer\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use UmberFirm\Bundle\PublicBundle\Event\Customer\CustomerResetPasswordEvent;

/**
 * Interface CustomerResetPasswordEventSubscriberInterface
 *
 * @package UmberFirm\Bundle\CustomerBundle\Event\Subscriber
 */
interface CustomerResetPasswordEventSubscriberInterface extends EventSubscriberInterface
{
    /**
     * @param CustomerResetPasswordEvent $event
     *
     * @return void
     */
    public function onResetPassword(CustomerResetPasswordEvent $event): void;

    /**
     * @param CustomerResetPasswordEvent $event
     *
     * @return void
     */
    public function onChangePassword(CustomerResetPasswordEvent $event): void;

    /**
     * @param CustomerResetPasswordEvent $event
     *
     * @return void
     */
    public function onConfirmResetPassword(CustomerResetPasswordEvent $event): void;
}
