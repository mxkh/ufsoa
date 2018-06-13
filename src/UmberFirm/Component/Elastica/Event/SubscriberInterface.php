<?php

declare(strict_types=1);

namespace UmberFirm\Component\Elastica\Event;

use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Interface SubscriberInterface
 *
 * @package UmberFirm\Component\Elastica\Event
 */
interface SubscriberInterface
{
    /**
     * @param UuidEntityInterface $entity
     *
     * @return bool
     */
    public function isObjectIndexable(UuidEntityInterface $entity): bool;

    /**
     * @param UuidEntityInterface $entity
     *
     * @return bool
     */
    public function isObjectPersistence(UuidEntityInterface $entity): bool;

    /**
     * @param UuidEntityInterface $entity
     *
     * @return void
     */
    public function replaceOne(UuidEntityInterface $entity): void;
}
