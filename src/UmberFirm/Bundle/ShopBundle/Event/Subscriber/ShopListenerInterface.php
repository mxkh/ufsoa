<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Event\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Interface ShopListenerInterface
 *
 * @package UmberFirm\Bundle\ShopBundle\Event\Subscriber
 */
interface ShopListenerInterface extends EventSubscriber
{
    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event): void;
}
