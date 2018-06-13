<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Component\Producer;

use OldSound\RabbitMqBundle\RabbitMq\Producer;
use UmberFirm\Bundle\CommonBundle\Component\Manager\ZendeskTicketManager;
use UmberFirm\Bundle\CommonBundle\Entity\Feedback;

/**
 * Class FeedbackProducer
 *
 * @package UmberFirm\Bundle\CommonBundle\Component\Producer
 */
class FeedbackProducer extends Producer implements FeedbackProducerInterface
{
    /**
     * {@inheritdoc}
     */
    public function syncCreateZendesk(Feedback $feedback): void
    {
        $messageBody = serialize([ZendeskTicketManager::CREATE_ACTION, $feedback]);
        $this->publish($messageBody);
    }

    /**
     * {@inheritdoc}
     */
    public function syncRemoveZendesk(Feedback $feedback): void
    {
        $messageBody = serialize([ZendeskTicketManager::REMOVE_ACTION, $feedback]);
        $this->publish($messageBody);
    }
}
