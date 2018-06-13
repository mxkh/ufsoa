<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Event\Subscriber;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Common\EventSubscriber;

/**
 * Interface CustomerListenerInterface
 *
 * @package UmberFirm\Bundle\CustomerBundle\Event\Subscriber
 */
interface CustomerListenerInterface extends EventSubscriber
{
    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event): void;
}
