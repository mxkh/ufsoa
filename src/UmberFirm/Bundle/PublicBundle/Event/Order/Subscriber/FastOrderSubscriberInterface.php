<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Event\Order\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use UmberFirm\Bundle\PublicBundle\Event\Order\FastOrderEventInterface;

/**
 * Interface FastOrderSubscriberInterface
 *
 * @package UmberFirm\Bundle\PublicBundle\Event\Customer\Subscriber
 */
interface FastOrderSubscriberInterface extends EventSubscriberInterface
{
    /**
     * @param FastOrderEventInterface $fastOrderEvent
     */
    public function onPlacement(FastOrderEventInterface $fastOrderEvent): void;
}
