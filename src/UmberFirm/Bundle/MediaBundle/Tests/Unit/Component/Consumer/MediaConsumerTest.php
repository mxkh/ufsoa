<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\Tests\Unit\Component\Consumer;

use PhpAmqpLib\Message\AMQPMessage;
use UmberFirm\Bundle\MediaBundle\Component\Consumer\MediaConsumer;
use UmberFirm\Bundle\MediaBundle\Component\Storage\MediaStorageInterface;

/**
 * Class MediaConsumerTest
 *
 * @package UmberFirm\Bundle\MediaBundle\Tests\Unit\Component\Consumer
 */
class MediaConsumerTest extends \PHPUnit_Framework_TestCase
{
    /** @var MediaStorageInterface */
    private $storage;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->storage = $this->getMockBuilder(MediaStorageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testExecuteSuccess()
    {
        $this->storage
            ->expects($this->once())
            ->method('delete')
            ->willReturn(true);

        $consumer = new MediaConsumer($this->storage);
        $result = $consumer->execute(new AMQPMessage(json_encode(['filename' => 'filename.jpg'])));

        $this->assertTrue($result);
    }

    public function testExecuteFailure()
    {
        $this->storage
            ->expects($this->once())
            ->method('delete')
            ->willReturn(false);

        $consumer = new MediaConsumer($this->storage);
        $result = $consumer->execute(new AMQPMessage(json_encode(['filename' => 'filename.jpg'])));

        $this->assertFalse($result);
    }
}
