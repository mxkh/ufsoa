<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Component\Consumer;

use PhpAmqpLib\Message\AMQPMessage;
use UmberFirm\Bundle\CommonBundle\Component\Manager\ZendeskTicketManagerInterface;

/**
 * Class FeedbackConsumer
 *
 * @package UmberFirm\Bundle\CommonBundle\Component\Consumer
 */
class FeedbackConsumer implements FeedbackConsumerInterface
{
    /**
     * @var ZendeskTicketManagerInterface
     */
    private $zendeskManager;

    /**
     * FeedbackConsumer constructor.
     *
     * @param ZendeskTicketManagerInterface $zendeskManager
     */
    public function __construct(ZendeskTicketManagerInterface $zendeskManager)
    {
        $this->zendeskManager = $zendeskManager;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(AMQPMessage $msg)
    {
        list($action, $feedback) = unserialize($msg->getBody());

        return $this->zendeskManager->execute($action, $feedback);
    }
}
