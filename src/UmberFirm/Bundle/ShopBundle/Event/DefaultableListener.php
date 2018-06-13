<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Event;

use UmberFirm\Bundle\ShopBundle\Entity\DefaultableInterface;
use UmberFirm\Bundle\ShopBundle\Event\EventDispatcher\ShopDefaultableEvent;

/**
 * Class DefaultableListener
 *
 * @package UmberFirm\Bundle\ShopBundle\Event
 */
class DefaultableListener
{
    /**
     * @param ShopDefaultableEvent $event
     */
    public function onDefault(ShopDefaultableEvent $event): void
    {
        if (false === $event->getDefaultable()->getIsDefault()) {
            return;
        }

        $iterator = $event->getDefaultable()->getShopDefaultables()->getIterator();
        foreach ($iterator as $key => $shopDefaultable) {
            /* @var DefaultableInterface $shopDefaultable */
            if ($event->getDefaultable() !== $shopDefaultable) {
                $shopDefaultable->setIsDefault(false);
                $event->getDefaultable()->getShopDefaultables()->set($key, $shopDefaultable);
            }
        }
    }

    /**
     * @param ShopDefaultableEvent $event
     */
    public function onCreate(ShopDefaultableEvent $event): void
    {
        if (0 === $event->getDefaultable()->getShopDefaultables()->count()) {
            $event->getDefaultable()->setIsDefault(true);
        }

        $this->onDefault($event);
    }
}
