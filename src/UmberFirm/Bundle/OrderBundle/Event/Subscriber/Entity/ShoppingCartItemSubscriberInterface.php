<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Event\Subscriber\Entity;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use UmberFirm\Bundle\OrderBundle\Event\Dispatcher\Entity\ShoppingCartItemPlacementEventInterface;

/**
 * Interface ShoppingCartItemSubscriberInterface
 *
 * @package UmberFirm\Bundle\OrderBundle\Event\Subscriber\Entity
 */
interface ShoppingCartItemSubscriberInterface extends EventSubscriberInterface
{
    /**
     * @param ShoppingCartItemPlacementEventInterface $event
     *
     * @return void
     */
    public function onPlacement(ShoppingCartItemPlacementEventInterface $event): void;
}
