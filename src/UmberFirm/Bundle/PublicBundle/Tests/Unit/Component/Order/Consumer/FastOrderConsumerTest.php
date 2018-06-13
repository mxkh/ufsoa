<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Order\Consumer;

use PhpAmqpLib\Message\AMQPMessage;
use UmberFirm\Bundle\PublicBundle\Component\Order\Consumer\FastOrderConsumer;
use UmberFirm\Bundle\PublicBundle\Component\Order\DataTransferObject\FastOrderDTOInterface;
use UmberFirm\Bundle\PublicBundle\Component\Order\Manager\FastOrderManagerInterface;

/**
 * Class FastOrderConsumerTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Order\Consumer
 */
class FastOrderConsumerTest extends \PHPUnit_Framework_TestCase
{
    /** @var FastOrderManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $fastOrderManager;

    /** @var AMQPMessage|\PHPUnit_Framework_MockObject_MockObject */
    private $amqpMessage;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->fastOrderManager = $this->createMock(FastOrderManagerInterface::class);
        $this->amqpMessage = $this->createMock(AMQPMessage::class);
    }

    public function testExecuteSuccess()
    {
        $this->fastOrderManager
            ->expects($this->once())
            ->method('manage')
            ->willReturn(true);

        $dto = $this->createMock(FastOrderDTOInterface::class);
        $this->amqpMessage
            ->expects($this->once())
            ->method('getBody')
            ->willReturn(serialize($dto));

        $consumer = new FastOrderConsumer($this->fastOrderManager);
        $result = $consumer->execute($this->amqpMessage);
        $this->assertTrue($result);
    }

    public function testExecuteFalse()
    {
        $this->fastOrderManager
            ->expects($this->once())
            ->method('manage')
            ->willReturn(false);

        $dto = $this->createMock(FastOrderDTOInterface::class);
        $this->amqpMessage
            ->expects($this->once())
            ->method('getBody')
            ->willReturn(serialize($dto));

        $consumer = new FastOrderConsumer($this->fastOrderManager);
        $result = $consumer->execute($this->amqpMessage);
        $this->assertFalse($result);
    }

}
