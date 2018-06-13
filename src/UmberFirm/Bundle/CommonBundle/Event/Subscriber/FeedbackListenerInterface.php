<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Event\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Interface FeedbackListenerInterface
 *
 * @package UmberFirm\Bundle\CommonBundle\Event\Subscriber
 */
interface FeedbackListenerInterface extends EventSubscriber
{
    /**
     * @param LifecycleEventArgs $eventArgs
     *
     * @return void
     */
    public function postPersist(LifecycleEventArgs $eventArgs): void;

    /**
     * @param LifecycleEventArgs $eventArgs
     *
     * @return void
     */
    public function postRemove(LifecycleEventArgs $eventArgs): void;
}
