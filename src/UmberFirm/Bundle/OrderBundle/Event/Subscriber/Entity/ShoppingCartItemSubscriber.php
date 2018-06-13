<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Event\Subscriber\Entity;

use UmberFirm\Bundle\OrderBundle\Event\Dispatcher\Entity\ShoppingCartItemPlacementEventInterface;

/**
 * Class ShoppingCartItemSubscriber
 *
 * @package UmberFirm\Bundle\OrderBundle\Event\Subscriber\Entity
 */
final class ShoppingCartItemSubscriber implements ShoppingCartItemSubscriberInterface
{
    /**
     * @deprecated
     *
     * ShoppingCartItemSubscriber constructor.
     *
     */
    public function __construct()
    {
        trigger_error("Deprecated class called.", E_USER_NOTICE);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ShoppingCartItemPlacementEventInterface::NAME => 'onPlacement',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function onPlacement(ShoppingCartItemPlacementEventInterface $event): void
    {
    }
}
