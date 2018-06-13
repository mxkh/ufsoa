<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Event\Subscriber;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Ramsey\Uuid\Uuid;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class ShopListener
 *
 * @package UmberFirm\Bundle\ShopBundle\Event\Subscriber
 */
class ShopListener implements ShopListenerInterface
{
    /**
     * {@inheritdoc}
     */
    public function prePersist(LifecycleEventArgs $event): void
    {
        if (false === ($event->getEntity() instanceof Shop)) {
            return;
        }
        /** @var Shop $shop */
        $shop = $event->getEntity();
        $shop->setApiKey(md5(Uuid::uuid4()->toString()));
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
        ];
    }
}
