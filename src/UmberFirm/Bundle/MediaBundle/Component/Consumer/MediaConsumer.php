<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\Component\Consumer;

use PhpAmqpLib\Message\AMQPMessage;
use UmberFirm\Bundle\MediaBundle\Component\Storage\MediaStorageInterface;

/**
 * Class MediaConsumer
 *
 * @package UmberFirm\Bundle\MediaBundle\Component\Consumer
 */
class MediaConsumer implements MediaConsumerInterface
{
    /**
     * @var MediaStorageInterface
     */
    private $storage;

    /**
     * MediaConsumer constructor.
     *
     * @param MediaStorageInterface $storage
     */
    public function __construct(MediaStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(AMQPMessage $message)
    {
        $body = json_decode($message->getBody());

        return $this->storage->delete($body->filename);
    }
}
