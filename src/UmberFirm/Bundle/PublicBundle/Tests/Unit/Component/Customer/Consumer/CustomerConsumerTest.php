<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Customer\Consumer;

use PhpAmqpLib\Message\AMQPMessage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\Templating\EngineInterface;
use UmberFirm\Bundle\NotificationBundle\Component\UniOne;
use UmberFirm\Bundle\PublicBundle\Component\Customer\Consumer\CustomerConsumer;
use UmberFirm\Bundle\PublicBundle\Component\Customer\DataTransferObject\CustomerConfirmationCodeInterface;

/**
 * Class CustomerConsumerTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Customer\Consumer
 */
class CustomerConsumerTest extends \PHPUnit_Framework_TestCase
{
    public function testExecuteSuccess()
    {
        /** @var CustomerConfirmationCodeInterface|\PHPUnit_Framework_MockObject_MockObject $confirmationCode */
        $confirmationCode = $this->createMock(CustomerConfirmationCodeInterface::class);
        $streaming = $this->createMock(StreamInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($streaming);
        $uniOne = $this->createMock(UniOne::class);
        $uniOne->method('sendEmail')->willReturn($response);
        $templating = $this->createMock(EngineInterface::class);

        $consumer = new CustomerConsumer();
        $consumer->setUniOne($uniOne);
        $consumer->setTemplating($templating);

        $result = $consumer->execute(new AMQPMessage(serialize($confirmationCode)));

        $this->assertTrue($result);
    }
}
