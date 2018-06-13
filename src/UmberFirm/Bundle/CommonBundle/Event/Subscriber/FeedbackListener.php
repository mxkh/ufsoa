<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Event\Subscriber;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use UmberFirm\Bundle\CommonBundle\Component\Producer\FeedbackProducerInterface;
use UmberFirm\Bundle\CommonBundle\Entity\Feedback;

/**
 * Class FeedbackListener
 *
 * @package UmberFirm\Bundle\CommonBundle\Event\Subscriber
 */
class FeedbackListener implements FeedbackListenerInterface
{
    /**
     * @var FeedbackProducerInterface
     */
    private $feedbackProducer;

    /**
     * FeedbackListener constructor.
     *
     * @param FeedbackProducerInterface $feedbackProducer
     */
    public function __construct(FeedbackProducerInterface $feedbackProducer)
    {
        $this->feedbackProducer = $feedbackProducer;
    }

    /**
     * {@inheritdoc}
     */
    public function postPersist(LifecycleEventArgs $eventArgs): void
    {
        /** @var Feedback $feedback */
        $feedback = $eventArgs->getEntity();
        if (false === $feedback instanceof Feedback) {
            return;
        }

        $this->feedbackProducer->syncCreateZendesk($feedback);
    }

    /**
     * {@inheritdoc}
     */
    public function postRemove(LifecycleEventArgs $eventArgs): void
    {
        /** @var Feedback $feedback */
        $feedback = $eventArgs->getEntity();
        if (false === $feedback instanceof Feedback) {
            return;
        }

        $this->feedbackProducer->syncRemoveZendesk($feedback);
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postRemove,
        ];
    }
}
