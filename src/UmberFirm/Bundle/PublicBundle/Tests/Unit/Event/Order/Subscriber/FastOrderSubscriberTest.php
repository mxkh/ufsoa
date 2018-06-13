<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Unit\Event\Order\Subscriber;

use Symfony\Component\Form\FormEvent;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Repository\CustomerRepository;
use UmberFirm\Bundle\OrderBundle\Entity\FastOrder;
use UmberFirm\Bundle\PublicBundle\Component\Customer\Manager\SignUp\CustomerSignUpManagerInterface;
use UmberFirm\Bundle\PublicBundle\Component\Order\DataTransferObject\Factory\OrderDataTransferObjectFactoryInterface;
use UmberFirm\Bundle\PublicBundle\Component\Order\DataTransferObject\FastOrderDTO;
use UmberFirm\Bundle\PublicBundle\Component\Order\Producer\FastOrderProducerInterface;
use UmberFirm\Bundle\PublicBundle\Event\Order\FastOrderEventInterface;
use UmberFirm\Bundle\PublicBundle\Event\Order\Subscriber\FastOrderSubscriber;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class FastOrderSubscriberTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Unit\Event\Order\Subscriber
 */
class FastOrderSubscriberTest extends \PHPUnit_Framework_TestCase
{
    /** @var CustomerSignUpManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $customerSignUpManager;

    /** @var OrderDataTransferObjectFactoryInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $orderDataTransferObjectFactory;

    /** @var FastOrderProducerInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $fastOrderProducer;

    /** @var FastOrderEventInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $fastOrderEvent;

    /** @var FormEvent|\PHPUnit_Framework_MockObject_MockObject */
    private $formEvent;

    /** @var CustomerRepository|\PHPUnit_Framework_MockObject_MockObject */
    private $customerRepository;

    /** @var FastOrder|\PHPUnit_Framework_MockObject_MockObject */
    private $fastOrder;

    /** @var FastOrderDTO|\PHPUnit_Framework_MockObject_MockObject */
    private $fastOrderDTO;

    protected function setUp()
    {
        $this->customerSignUpManager = $this->getMockBuilder(CustomerSignUpManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->orderDataTransferObjectFactory = $this->getMockBuilder(OrderDataTransferObjectFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->fastOrderProducer = $this->getMockBuilder(FastOrderProducerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->formEvent = $this->getMockBuilder(FormEvent::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->fastOrderEvent = $this->getMockBuilder(FastOrderEventInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerRepository = $this->getMockBuilder(CustomerRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->fastOrder = $this->getMockBuilder(FastOrder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->fastOrderDTO = $this->getMockBuilder(FastOrderDTO::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testOnPlacementCustomerAuthorized()
    {
        $this->fastOrderEvent->expects($this->any())->method('getFastOrder')->willReturn($this->fastOrder);
        $this->fastOrderEvent->expects($this->once())->method('getCustomer')->willReturn(new Customer());
        $this->fastOrderEvent->expects($this->once())->method('getShop')->willReturn(new Shop());

        $this->customerRepository->expects($this->never())->method('loadCustomerByPhone');
        $this->orderDataTransferObjectFactory
            ->expects($this->once())
            ->method('createFastOrderDTO')
            ->willReturn($this->fastOrderDTO);

        $this->fastOrderProducer->expects($this->once())->method('save');

        $fastOrderSubscriber = new FastOrderSubscriber(
            $this->customerRepository,
            $this->customerSignUpManager,
            $this->orderDataTransferObjectFactory,
            $this->fastOrderProducer
        );

        $fastOrderSubscriber->onPlacement($this->fastOrderEvent);
    }

    public function testOnPlacementCustomerNotAuthorized()
    {
        $this->fastOrderEvent->expects($this->any())->method('getFastOrder')->willReturn($this->fastOrder);
        $this->fastOrderEvent->expects($this->once())->method('getCustomer')->willReturn(null);
        $this->fastOrderEvent->expects($this->once())->method('getShop')->willReturn(new Shop());

        $this->customerRepository->expects($this->once())->method('loadCustomerByPhone')->willReturn(new Customer());

        $this->orderDataTransferObjectFactory
            ->expects($this->once())
            ->method('createFastOrderDTO')
            ->willReturn($this->fastOrderDTO);

        $this->fastOrderProducer->expects($this->once())->method('save');

        $fastOrderSubscriber = new FastOrderSubscriber(
            $this->customerRepository,
            $this->customerSignUpManager,
            $this->orderDataTransferObjectFactory,
            $this->fastOrderProducer
        );

        $fastOrderSubscriber->onPlacement($this->fastOrderEvent);
    }

    public function testOnPlacementCustomerNotRegistered()
    {
        $this->fastOrderEvent->expects($this->any())->method('getFastOrder')->willReturn($this->fastOrder);
        $this->fastOrderEvent->expects($this->once())->method('getCustomer')->willReturn(null);
        $this->fastOrderEvent->expects($this->once())->method('getShop')->willReturn(new Shop());

        $this->customerRepository->expects($this->once())->method('loadCustomerByPhone')->willReturn(null);
        $this->customerSignUpManager->expects($this->once())->method('complete');

        $this->orderDataTransferObjectFactory
            ->expects($this->once())
            ->method('createFastOrderDTO')
            ->willReturn($this->fastOrderDTO);

        $this->fastOrderProducer->expects($this->once())->method('save');

        $fastOrderSubscriber = new FastOrderSubscriber(
            $this->customerRepository,
            $this->customerSignUpManager,
            $this->orderDataTransferObjectFactory,
            $this->fastOrderProducer
        );

        $fastOrderSubscriber->onPlacement($this->fastOrderEvent);
    }

    public function testGetSubscribedEvents()
    {
        $this->assertInternalType('array', FastOrderSubscriber::getSubscribedEvents());
    }
}
