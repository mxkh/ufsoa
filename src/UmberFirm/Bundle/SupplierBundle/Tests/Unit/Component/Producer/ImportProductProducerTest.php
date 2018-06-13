<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Tests\Unit\Component\Producer;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AbstractConnection;
use Ramsey\Uuid\Uuid;
use UmberFirm\Bundle\SupplierBundle\Component\Producer\ImportProductProducer;
use UmberFirm\Bundle\SupplierBundle\Entity\Import;

/**
 * Class ImportProductProducerTest
 *
 * @package UmberFirm\Bundle\SupplierBundle\Tests\Unit\Component\Producer
 */
class ImportProductProducerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|AbstractConnection */
    private $connection;

    /** @var \PHPUnit_Framework_MockObject_MockObject|AMQPChannel */
    private $channel;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->connection = $this->createMock(AbstractConnection::class);
        $this->channel = $this->createMock(AMQPChannel::class);
    }

    public function testImport()
    {
        $this->connection
            ->method('channel')
            ->willReturn($this->channel);

        $this->channel
            ->expects($this->once())
            ->method('basic_publish');

        /** @var Import|\PHPUnit_Framework_MockObject_MockObject $import */
        $import = $this->createMock(Import::class);
        $import
            ->expects($this->any())
            ->method('getId')
            ->willReturn(Uuid::uuid4());

        $producer = new ImportProductProducer($this->connection);
        $producer->disableAutoSetupFabric();
        $producer->setExchangeOptions(["name" => "supplier", "type" => "direct"]);
        $producer->import($import);
    }

    /**
     * @expectedException \TypeError
     */
    public function testImportTypeError()
    {
        $producer = new ImportProductProducer($this->connection);
        $producer->import(new \stdClass());
    }
}
