<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Event\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;

/**
 * Interface ImportEventListenerInterface
 *
 * @package UmberFirm\Bundle\SupplierBundle\Event\EventListener
 */
interface ImportEventListenerInterface
{
    /**
     * The prePersist event occurs before the respective EntityManager persist operation for that entity is executed.
     * It should be noted that this event is only triggered on initial persist of
     * an entity (i.e. it does not trigger on future updates).
     *
     * @param LifecycleEventArgs $event
     *
     * @throws \Exception
     */
    public function prePersist(LifecycleEventArgs $event): void;

    /**
     * @param PostFlushEventArgs $args
     */
    public function postFlush(PostFlushEventArgs $args): void;

    /**
     * @param LifecycleEventArgs $event
     */
    public function postUpdate(LifecycleEventArgs $event): void;
}
