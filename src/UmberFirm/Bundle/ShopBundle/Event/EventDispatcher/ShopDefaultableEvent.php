<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Event\EventDispatcher;

use Symfony\Component\EventDispatcher\Event;
use UmberFirm\Bundle\ShopBundle\Entity\DefaultableInterface;

/**
 * Class ShopOnDefaultChangeEvent
 *
 * @package UmberFirm\Component\EventDispatcher
 */
class ShopDefaultableEvent extends Event
{
    /**
     * @var DefaultableInterface
     */
    protected $defaultable;

    /**
     * @return DefaultableInterface
     */
    public function getDefaultable(): DefaultableInterface
    {
        return $this->defaultable;
    }

    /**
     * @param DefaultableInterface $defaultable
     *
     * @return ShopDefaultableEvent
     */
    public function create(DefaultableInterface $defaultable): ShopDefaultableEvent
    {
        $this->defaultable = $defaultable;

        return $this;
    }
}
