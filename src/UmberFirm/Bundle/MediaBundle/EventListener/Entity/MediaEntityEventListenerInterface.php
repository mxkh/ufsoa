<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\EventListener\Entity;

use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Interface MediaEntityEventListenerInterface
 *
 * @package UmberFirm\Bundle\MediaBundle\EventListener\Entity
 */
interface MediaEntityEventListenerInterface
{
    /**
     * The prePersist event occurs before the respective EntityManager persist operation for that entity is executed.
     * It should be noted that this event is only triggered on
     * initial persist of an entity (i.e. it does not trigger on future updates).
     *
     * @param LifecycleEventArgs $event
     *
     * @throws \Exception
     *
     * @return void
     */
    public function prePersist(LifecycleEventArgs $event): void;

    /**
     * The preRemove event occurs before the respective EntityManager remove operation for that entity is executed.
     * It is not called for a DQL DELETE statement.
     *
     * @param LifecycleEventArgs $event
     *
     * @return void
     */
    public function preRemove(LifecycleEventArgs $event): void;
}
