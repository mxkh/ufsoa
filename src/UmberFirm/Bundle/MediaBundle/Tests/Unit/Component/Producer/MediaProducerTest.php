<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\Tests\Unit\Component\Producer;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AbstractConnection;
use UmberFirm\Bundle\MediaBundle\Component\Producer\MediaProducer;

/**
 * Class MediaProducerTest
 *
 * @package UmberFirm\Bundle\MediaBundle\Tests\Unit\Component\Producer
 */
class MediaProducerTest extends \PHPUnit_Framework_TestCase
{
    /** @var AbstractConnection */
    private $connection;

    /** @var AMQPChannel */
    private $channel;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->connection = $this->getMockBuilder(AbstractConnection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->channel = $this->getMockBuilder(AMQPChannel::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testDelete()
    {
        $this->connection
            ->method('channel')
            ->willReturn($this->channel);

        $this->channel
            ->expects($this->once())
            ->method('basic_publish');

        $producer = new MediaProducer($this->connection);
        $producer->disableAutoSetupFabric();
        $producer->setExchangeOptions(['name' => 'media', 'type' => 'direct']);
        $producer->delete('filename.jpg');
    }

    /**
     * @expectedException \TypeError
     */
    public function testDeleteTypeError()
    {
        $this->connection
            ->method('channel')
            ->willReturn($this->channel);

        $this->channel
            ->expects($this->never())
            ->method('basic_publish');

        $producer = new MediaProducer($this->connection);
        $producer->delete(123);
    }
}
