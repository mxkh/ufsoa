<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Order\Manager;

use Exception;
use Doctrine\ORM\EntityManagerInterface;
use UmberFirm\Bundle\OrderBundle\Component\Factory\OrderFactoryInterface;
use UmberFirm\Bundle\OrderBundle\Entity\Order;
use UmberFirm\Bundle\PublicBundle\Component\Order\DataTransferObject\FastOrderDTOInterface;
use UmberFirm\Bundle\PublicBundle\Component\Order\Manager\FastOrderManager;

/**
 * Class FastOrderManagerTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Order\Manager
 */
class FastOrderManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $entityManager;

    /**
     * @var FastOrderDTOInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $fastOrderDTO;

    /**
     * @var OrderFactoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $orderFactory;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->orderFactory = $this->getMockBuilder(OrderFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->fastOrderDTO = $this->getMockBuilder(FastOrderDTOInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testManage()
    {
        $this->orderFactory
            ->expects($this->once())
            ->method('createFromFastOrderDTO')
            ->willReturn(new Order());

        $this->entityManager
            ->expects($this->once())
            ->method('persist');

        $this->entityManager
            ->expects($this->once())
            ->method('flush');


        $fastOrderManager = new FastOrderManager($this->entityManager, $this->orderFactory);

        $this->assertTrue($fastOrderManager->manage($this->fastOrderDTO));
    }

    public function testManageFailure()
    {
        $this->orderFactory
            ->expects($this->once())
            ->method('createFromFastOrderDTO')
            ->willReturn(new Order());

        $this->entityManager
            ->expects($this->once())
            ->method('persist');

        $this->entityManager
            ->expects($this->once())
            ->method('flush')
            ->willThrowException(new Exception());

        $fastOrderManager = new FastOrderManager($this->entityManager, $this->orderFactory);

        $this->assertFalse($fastOrderManager->manage($this->fastOrderDTO));
    }
}
