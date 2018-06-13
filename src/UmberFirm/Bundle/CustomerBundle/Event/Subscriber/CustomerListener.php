<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Event\Subscriber;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Ramsey\Uuid\Uuid;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class CustomerListener
 *
 * @package UmberFirm\Bundle\CustomerBundle\Event\Subscriber
 */
class CustomerListener implements CustomerListenerInterface
{
    /**
     * {@inheritdoc}
     */
    public function prePersist(LifecycleEventArgs $event): void
    {
        if (false === ($event->getEntity() instanceof Customer)) {
            return;
        }
        /** @var Customer $customer */
        $customer = $event->getEntity();

        if (false === $customer->getShop() instanceof Shop) {
            return;
        }

        $customer->setToken(md5(sprintf("%s%s", $customer->getShop()->getId()->toString(), Uuid::uuid4()->toString())));
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
