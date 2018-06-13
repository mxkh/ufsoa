<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Tests\Unit\Component\Consumer;

use PhpAmqpLib\Message\AMQPMessage;
use UmberFirm\Bundle\CommonBundle\Component\Consumer\FeedbackConsumer;
use UmberFirm\Bundle\CommonBundle\Component\Manager\ZendeskTicketManager;
use UmberFirm\Bundle\CommonBundle\Component\Manager\ZendeskTicketManagerInterface;
use UmberFirm\Bundle\CommonBundle\Entity\Feedback;

/**
 * Class FeedbackConsumerTest
 *
 * @package UmberFirm\Bundle\CommonBundle\Tests\Unit\Component\Consumer
 */
class FeedbackConsumerTest extends \PHPUnit_Framework_TestCase
{
    /** @var AMQPMessage|\PHPUnit_Framework_MockObject_MockObject */
    private $amqpMessage;

    /** @var ZendeskTicketManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $zendeskTicketManager;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->amqpMessage = $this->createMock(AMQPMessage::class);
        $this->zendeskTicketManager = $this->createMock(ZendeskTicketManagerInterface::class);
    }

    public function testExecute()
    {
        $this->amqpMessage
            ->expects($this->once())
            ->method('getBody')
            ->willReturn(serialize([ZendeskTicketManager::CREATE_ACTION, new Feedback()]));

        $this->zendeskTicketManager
            ->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $consumer = new FeedbackConsumer($this->zendeskTicketManager);
        $result = $consumer->execute($this->amqpMessage);
        $this->assertTrue($result);
    }

    public function testExecuteFailure()
    {
        $this->amqpMessage
            ->expects($this->once())
            ->method('getBody')
            ->willReturn(serialize([ZendeskTicketManager::CREATE_ACTION, new Feedback()]));

        $this->zendeskTicketManager
            ->expects($this->once())
            ->method('execute')
            ->willReturn(false);

        $consumer = new FeedbackConsumer($this->zendeskTicketManager);
        $result = $consumer->execute($this->amqpMessage);
        $this->assertFalse($result);
    }
}
