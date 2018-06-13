<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Tests\Unit\Component\Consumer;

use PhpAmqpLib\Message\AMQPMessage;
use UmberFirm\Bundle\SupplierBundle\Component\Consumer\ImportProductConsumer;
use UmberFirm\Bundle\SupplierBundle\Component\Manager\ImportConsumerManagerInterface;

/**
 * Class ImportProductConsumerTest
 *
 * @package UmberFirm\Bundle\SupplierBundle\Tests\Unit\Component\Consumer
 */
class ImportProductConsumerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ImportConsumerManagerInterface
     */
    private $importProductManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|AMQPMessage
     */
    private $AMQPMessage;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->importProductManager = $this->createMock(ImportConsumerManagerInterface::class);
        $this->AMQPMessage = $this->createMock(AMQPMessage::class);
    }

    public function testExecuteSuccess()
    {
        $this->importProductManager
            ->expects($this->once())
            ->method('manage')
            ->willReturn(true);

        $importProductConsumer = new ImportProductConsumer($this->importProductManager);

        $this->AMQPMessage
            ->expects($this->once())
            ->method('getBody')
            ->willReturn('{"import":"uid"}');

        $result = $importProductConsumer->execute($this->AMQPMessage);
        $this->assertTrue($result);
    }

    public function testExecuteFailed()
    {
        $this->importProductManager
            ->expects($this->once())
            ->method('manage')
            ->willReturn(false);

        $importProductConsumer = new ImportProductConsumer($this->importProductManager);

        $this->AMQPMessage
            ->expects($this->once())
            ->method('getBody')
            ->willReturn('{"import":"uid"}');

        $result = $importProductConsumer->execute($this->AMQPMessage);
        $this->assertFalse($result);
    }
}
