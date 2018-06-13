<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Tests\Unit\Component\Producer;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AbstractConnection;
use UmberFirm\Bundle\CommonBundle\Component\Producer\FeedbackProducer;
use UmberFirm\Bundle\CommonBundle\Entity\Feedback;

/**
 * Class FeedbackProducerTest
 *
 * @package UmberFirm\Bundle\CommonBundle\Tests\Unit\Component\Producer
 */
class FeedbackProducerTest extends \PHPUnit_Framework_TestCase
{
    /** @var AbstractConnection|\PHPUnit_Framework_MockObject_MockObject */
    private $connection;

    /** @var AMQPChannel|\PHPUnit_Framework_MockObject_MockObject */
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

    public function testSyncCreateZendesk()
    {
        $this->connection
            ->method('channel')
            ->willReturn($this->channel);

        $this->channel
            ->expects($this->once())
            ->method('basic_publish');

        $feedback = $this->createMock(Feedback::class);
        $producer = new FeedbackProducer($this->connection);
        $producer->disableAutoSetupFabric();
        $producer->setExchangeOptions(["name" => "feedback", "type" => "direct"]);
        $producer->syncCreateZendesk($feedback);
    }

    public function testSyncRemoveZendesk()
    {
        $this->connection
            ->method('channel')
            ->willReturn($this->channel);

        $this->channel
            ->expects($this->once())
            ->method('basic_publish');

        $feedback = $this->createMock(Feedback::class);
        $producer = new FeedbackProducer($this->connection);
        $producer->disableAutoSetupFabric();
        $producer->setExchangeOptions(["name" => "feedback", "type" => "direct"]);
        $producer->syncRemoveZendesk($feedback);
    }
}
