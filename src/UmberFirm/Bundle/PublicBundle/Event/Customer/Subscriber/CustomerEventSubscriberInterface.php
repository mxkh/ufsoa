<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Event\Customer\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use UmberFirm\Bundle\PublicBundle\Event\Customer\CustomerEventInterface;

/**
 * Interface CustomerEventSubscriberInterface
 *
 * @package UmberFirm\Bundle\CustomerBundle\Event\Subscriber
 */
interface CustomerEventSubscriberInterface extends EventSubscriberInterface
{
    /**
     * @param CustomerEventInterface $event
     *
     * @return void
     */
    public function onCustomerSignUp(CustomerEventInterface $event): void;

    /**
     * @param CustomerEventInterface $event
     *
     * @return void
     */
    public function onCustomerConfirm(CustomerEventInterface $event): void;
}
