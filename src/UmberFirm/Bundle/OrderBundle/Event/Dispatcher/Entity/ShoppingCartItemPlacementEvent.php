<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Event\Dispatcher\Entity;

use Symfony\Component\EventDispatcher\Event;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCartItem;

/**
 * Class ShoppingCartItemPlacementEvent
 *
 * @package UmberFirm\Bundle\OrderBundle\Event\Dispatcher\Entity
 */
final class ShoppingCartItemPlacementEvent extends Event implements ShoppingCartItemPlacementEventInterface
{
    /**
     * @var ShoppingCartItem
     */
    protected $shoppingCartItem;

    /**
     * @deprecated
     *
     * ShoppingCartItemPlacementEvent constructor.
     */
    public function __construct()
    {
        trigger_error("Deprecated class called.", E_USER_NOTICE);
    }

    /**
     * {@inheritdoc}
     */
    public function setShoppingCartItem(ShoppingCartItem $shoppingCartItem): ShoppingCartItemPlacementEventInterface
    {
        $this->shoppingCartItem = $shoppingCartItem;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getShoppingCartItem(): ShoppingCartItem
    {
        return $this->shoppingCartItem;
    }
}
