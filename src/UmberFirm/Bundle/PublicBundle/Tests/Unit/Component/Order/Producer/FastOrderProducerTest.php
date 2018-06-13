<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Order\Producer;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AbstractConnection;
use UmberFirm\Bundle\PublicBundle\Component\Order\DataTransferObject\FastOrderDTOInterface;
use UmberFirm\Bundle\PublicBundle\Component\Order\Producer\FastOrderProducer;

/**
 * Class FastOrderProducerTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Order\Producer
 */
class FastOrderProducerTest extends \PHPUnit_Framework_TestCase
{
    /** @var AbstractConnection|\PHPUnit_Framework_MockObject_MockObject */
    private $connection;

    /** @var AMQPChannel|\PHPUnit_Framework_MockObject_MockObject */
    private $channel;

    /** @var FastOrderDTOInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $fastOrderDTO;

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

    public function testSave()
    {
        $this->fastOrderDTO = $this->createMock(FastOrderDTOInterface::class);

        $this->connection
            ->method('channel')
            ->willReturn($this->channel);

        $this->channel
            ->expects($this->once())
            ->method('basic_publish');

        $producer = new FastOrderProducer($this->connection);
        $producer->disableAutoSetupFabric();
        $producer->setExchangeOptions(['name' => 'fast-order', 'type' => 'direct']);
        $producer->save($this->fastOrderDTO);
    }
}
