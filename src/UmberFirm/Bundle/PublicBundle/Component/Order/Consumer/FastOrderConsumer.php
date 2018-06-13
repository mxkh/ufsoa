<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Order\Consumer;

use PhpAmqpLib\Message\AMQPMessage;
use UmberFirm\Bundle\PublicBundle\Component\Order\Manager\FastOrderManagerInterface;

/**
 * Class FastOrderConsumer
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Order\Consumer
 */
class FastOrderConsumer implements FastOrderConsumerInterface
{
    /**
     * @var FastOrderManagerInterface
     */
    private $fastOrderManager;

    /**
     * FastOrderConsumer constructor.
     *
     * @param FastOrderManagerInterface $fastOrderManager
     */
    public function __construct(FastOrderManagerInterface $fastOrderManager)
    {
        $this->fastOrderManager = $fastOrderManager;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(AMQPMessage $msg)
    {
        return $this->fastOrderManager->manage(unserialize($msg->getBody()));
    }
}
