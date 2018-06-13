<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Customer;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AbstractConnection;
use UmberFirm\Bundle\PublicBundle\Component\Customer\DataTransferObject\CustomerConfirmationCodeInterface;
use UmberFirm\Bundle\PublicBundle\Component\Customer\Producer\CustomerProducer;

/**
 * Class CustomerProducerTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Customer
 */
class CustomerProducerTest extends \PHPUnit_Framework_TestCase
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

    public function testSendConfirmationCode()
    {
        /** @var CustomerConfirmationCodeInterface|\PHPUnit_Framework_MockObject_MockObject $confirmationCode */
        $confirmationCode = $this->createMock(CustomerConfirmationCodeInterface::class);

        $this->connection
            ->method('channel')
            ->willReturn($this->channel);

        $this->channel
            ->expects($this->once())
            ->method('basic_publish');

        $producer = new CustomerProducer($this->connection);
        $producer->disableAutoSetupFabric();
        $producer->setExchangeOptions(['name' => 'customer', 'type' => 'direct']);
        $producer->sendConfirmationCode($confirmationCode);
    }
}
