<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Order\Manager;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\Promocode;
use UmberFirm\Bundle\OrderBundle\Entity\PromocodeEnum;
use UmberFirm\Bundle\PublicBundle\Component\Order\Factory\PromocodeFilterFactoryInterface;
use UmberFirm\Bundle\PublicBundle\Component\Order\Filter\PromocodeFilterInterface;
use UmberFirm\Bundle\PublicBundle\Component\Order\Manager\PromocodeManager;

/**
 * Class PromocodeManagerTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Order\Manager
 */
class PromocodeManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testVerify()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|PromocodeFilterInterface $promocodeFilter */
        $promocodeFilter = $this->createMock(PromocodeFilterInterface::class);
        $promocodeFilter->expects($this->once())->method('customer')->willReturnSelf();
        $promocodeFilter->expects($this->once())->method('start')->willReturnSelf();
        $promocodeFilter->expects($this->once())->method('finish')->willReturnSelf();
        $promocodeFilter->expects($this->once())->method('reusable')->willReturnSelf();
        $promocodeFilter->expects($this->once())->method('limiting')->willReturnSelf();
        $promocodeFilter->expects($this->once())->method('getResult')->willReturn(true);

        /** @var \PHPUnit_Framework_MockObject_MockObject|PromocodeFilterFactoryInterface $promocodeFilterFactory */
        $promocodeFilterFactory = $this->createMock(PromocodeFilterFactoryInterface::class);
        $promocodeFilterFactory->expects($this->once())->method('createFilter')->willReturn($promocodeFilter);
        $promocodeManager = new PromocodeManager($promocodeFilterFactory);

        /** @var \PHPUnit_Framework_MockObject_MockObject|Customer $customer */
        $customer = $this->createMock(Customer::class);
        /** @var \PHPUnit_Framework_MockObject_MockObject|Promocode $promocode */
        $promocode = $this->createMock(Promocode::class);

        $this->assertTrue($promocodeManager->verify($promocode, $customer));
    }

    public function testCalculate()
    {
        $promocodeEnum = $this->createMock(PromocodeEnum::class);
        $promocodeEnum->expects($this->once())->method('getCalculate')->willReturn('%s * (1 - (%s / 100))');
        /** @var \PHPUnit_Framework_MockObject_MockObject|Promocode $promocode */
        $promocode = $this->createMock(Promocode::class);
        $promocode->expects($this->once())->method('getPromocodeEnum')->willReturn($promocodeEnum);
        $promocode->expects($this->once())->method('getValue')->willReturn(70);

        /** @var \PHPUnit_Framework_MockObject_MockObject|PromocodeFilterFactoryInterface $promocodeFilterFactory */
        $promocodeFilterFactory = $this->createMock(PromocodeFilterFactoryInterface::class);
        $promocodeManager = new PromocodeManager($promocodeFilterFactory);
        $this->equalTo(700.00, $promocodeManager->calculate($promocode,1000.00));
    }

    public function testIncrementUsed()
    {
        $promocode = new Promocode();

        /** @var \PHPUnit_Framework_MockObject_MockObject|PromocodeFilterFactoryInterface $promocodeFilterFactory */
        $promocodeFilterFactory = $this->createMock(PromocodeFilterFactoryInterface::class);
        $promocodeManager = new PromocodeManager($promocodeFilterFactory);
        $this->assertEquals(0, $promocode->getUsed());
        $this->assertInstanceOf(Promocode::class, $promocodeManager->incrementUsed($promocode));
        $this->assertEquals(1, $promocode->getUsed());
    }
}
