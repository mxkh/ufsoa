<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Component\Producer;

use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use UmberFirm\Bundle\CommonBundle\Entity\Feedback;

/**
 * Interface FeedbackProducerInterface
 *
 * @package UmberFirm\Bundle\CommonBundle\Component\Producer
 */
interface FeedbackProducerInterface extends ProducerInterface
{
    /**
     * @param Feedback $feedback
     *
     * @return void
     */
    public function syncCreateZendesk(Feedback $feedback): void;


    /**
     * @param Feedback $feedback
     *
     * @return void
     */
    public function syncRemoveZendesk(Feedback $feedback): void;
}
