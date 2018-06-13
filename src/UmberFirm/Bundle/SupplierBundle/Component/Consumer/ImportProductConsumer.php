<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Component\Consumer;

use PhpAmqpLib\Message\AMQPMessage;
use UmberFirm\Bundle\SupplierBundle\Component\Manager\ImportConsumerManagerInterface;

/**
 * Class ImportProductConsumer
 *
 * @package UmberFirm\Bundle\SupplierBundle\Component\Consumer
 */
final class ImportProductConsumer implements ImportConsumerInterface
{
    /**
     * @var ImportConsumerManagerInterface
     */
    private $importConsumerManager;

    /**
     * ImportProductConsumer constructor.
     *
     * @param ImportConsumerManagerInterface $importConsumerManager
     */
    public function __construct(ImportConsumerManagerInterface $importConsumerManager)
    {
        $this->importConsumerManager = $importConsumerManager;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(AMQPMessage $msg)
    {
        $message = json_decode($msg->getBody(), true);

        return $this->importConsumerManager->manage($message['import']);
    }
}
