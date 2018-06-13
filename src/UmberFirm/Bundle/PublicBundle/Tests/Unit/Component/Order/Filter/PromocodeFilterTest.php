<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Order\Filter;

use Ramsey\Uuid\Uuid;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\Promocode;
use UmberFirm\Bundle\PublicBundle\Component\Order\Filter\PromocodeFilter;
use UmberFirm\Bundle\PublicBundle\Component\Order\Filter\PromocodeFilterInterface;

/**
 * Class PromocodeFilterTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Order\Filter
 */
class PromocodeFilterTest extends \PHPUnit_Framework_TestCase
{
    /** @var PromocodeFilterInterface */
    private $promocodeFilter;

    /** @var \PHPUnit_Framework_MockObject_MockObject|Customer $customer */
    private $customer;

    /** @var \PHPUnit_Framework_MockObject_MockObject|Promocode $promocode */
    private $promocode;

    /** @var \PHPUnit_Framework_MockObject_MockObject|Uuid $promocode */
    private $uuid;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->customer = $this->createMock(Customer::class);
        $this->promocode = $this->createMock(Promocode::class);
        $this->uuid = $this->createMock(Uuid::class);
    }

    public function testCustomerNull()
    {
        $this->promocode->expects($this->once())->method('getCustomer')->willReturn(null);
        $this->promocodeFilter = new PromocodeFilter($this->promocode, $this->customer);
        $this->assertInstanceOf(PromocodeFilterInterface::class, $this->promocodeFilter->customer());
        $this->assertTrue($this->promocodeFilter->getResult());
    }

    public function testCustomerPropertyNull()
    {
        $this->uuid->expects($this->any())->method('toString')->willReturn(Uuid::uuid4());
        $this->customer->expects($this->any())->method('getId')->willReturn($this->uuid);
        $this->promocode->expects($this->any())->method('getCustomer')->willReturn($this->customer);
        $this->promocodeFilter = new PromocodeFilter($this->promocode, null);
        $this->assertInstanceOf(PromocodeFilterInterface::class, $this->promocodeFilter->customer());
        $this->assertFalse($this->promocodeFilter->getResult());
    }

    public function testCustomerSuccess()
    {
        $this->uuid->expects($this->any())->method('toString')->willReturn(Uuid::uuid4());
        $this->customer->expects($this->any())->method('getId')->willReturn($this->uuid);
        $this->promocode->expects($this->any())->method('getCustomer')->willReturn($this->customer);
        $this->promocodeFilter = new PromocodeFilter($this->promocode, $this->customer);
        $this->assertInstanceOf(PromocodeFilterInterface::class, $this->promocodeFilter->customer());
        $this->assertTrue($this->promocodeFilter->getResult());
    }

    public function testCustomerFailure()
    {
        $this->uuid->expects($this->at(0))->method('toString')->willReturn(Uuid::uuid4());
        $this->uuid->expects($this->at(1))->method('toString')->willReturn(Uuid::uuid4());
        $this->customer->expects($this->any())->method('getId')->willReturn($this->uuid);
        $this->promocode->expects($this->any())->method('getCustomer')->willReturn($this->customer);
        $this->promocodeFilter = new PromocodeFilter($this->promocode, $this->customer);
        $this->assertInstanceOf(PromocodeFilterInterface::class, $this->promocodeFilter->customer());
        $this->assertFalse($this->promocodeFilter->getResult());
    }

    public function testStartNull()
    {
        $this->promocode->expects($this->once())->method('getStart')->willReturn(null);
        $this->promocodeFilter = new PromocodeFilter($this->promocode, $this->customer);
        $this->assertInstanceOf(PromocodeFilterInterface::class, $this->promocodeFilter->start());
        $this->assertTrue($this->promocodeFilter->getResult());
    }

    public function testStartSuccess()
    {
        $this->promocode->expects($this->any())->method('getStart')->willReturn(new \DateTime('2000-01-01'));
        $this->promocodeFilter = new PromocodeFilter($this->promocode, $this->customer);
        $this->assertInstanceOf(PromocodeFilterInterface::class, $this->promocodeFilter->start());
        $this->assertTrue($this->promocodeFilter->getResult());
    }

    public function testStartFailure()
    {
        $this->promocode->expects($this->any())->method('getStart')->willReturn(new \DateTime('2150-01-01'));
        $this->promocodeFilter = new PromocodeFilter($this->promocode, $this->customer);
        $this->assertInstanceOf(PromocodeFilterInterface::class, $this->promocodeFilter->start());
        $this->assertFalse($this->promocodeFilter->getResult());
    }

    public function testFinishNull()
    {
        $this->promocode->expects($this->once())->method('getFinish')->willReturn(null);
        $this->promocodeFilter = new PromocodeFilter($this->promocode, $this->customer);
        $this->assertInstanceOf(PromocodeFilterInterface::class, $this->promocodeFilter->finish());
        $this->assertTrue($this->promocodeFilter->getResult());
    }

    public function testFinishSuccess()
    {
        $this->promocode->expects($this->any())->method('getFinish')->willReturn(new \DateTime('2150-01-01'));
        $this->promocodeFilter = new PromocodeFilter($this->promocode, $this->customer);
        $this->assertInstanceOf(PromocodeFilterInterface::class, $this->promocodeFilter->finish());
        $this->assertTrue($this->promocodeFilter->getResult());
    }

    public function testFinishFailure()
    {
        $this->promocode->expects($this->any())->method('getFinish')->willReturn(new \DateTime('2000-01-01'));
        $this->promocodeFilter = new PromocodeFilter($this->promocode, $this->customer);
        $this->assertInstanceOf(PromocodeFilterInterface::class, $this->promocodeFilter->finish());
        $this->assertFalse($this->promocodeFilter->getResult());
    }

    public function testReusableNull()
    {
        $this->promocode->expects($this->once())->method('getIsReusable')->willReturn(true);
        $this->promocodeFilter = new PromocodeFilter($this->promocode, $this->customer);
        $this->assertInstanceOf(PromocodeFilterInterface::class, $this->promocodeFilter->reusable());
        $this->assertTrue($this->promocodeFilter->getResult());
    }

    public function testReusableSuccess()
    {
        $this->promocode->expects($this->any())->method('getIsReusable')->willReturn(false);
        $this->promocode->expects($this->any())->method('getUsed')->willReturn(0);
        $this->promocodeFilter = new PromocodeFilter($this->promocode, $this->customer);
        $this->assertInstanceOf(PromocodeFilterInterface::class, $this->promocodeFilter->reusable());
        $this->assertTrue($this->promocodeFilter->getResult());
    }

    public function testReusableFailure()
    {
        $this->promocode->expects($this->any())->method('getIsReusable')->willReturn(false);
        $this->promocode->expects($this->any())->method('getUsed')->willReturn(1);
        $this->promocodeFilter = new PromocodeFilter($this->promocode, $this->customer);
        $this->assertInstanceOf(PromocodeFilterInterface::class, $this->promocodeFilter->reusable());
        $this->assertFalse($this->promocodeFilter->getResult());
    }

    public function testLimitingNull()
    {
        $this->promocode->expects($this->once())->method('getLimiting')->willReturn(0);
        $this->promocodeFilter = new PromocodeFilter($this->promocode, $this->customer);
        $this->assertInstanceOf(PromocodeFilterInterface::class, $this->promocodeFilter->limiting());
        $this->assertTrue($this->promocodeFilter->getResult());
    }

    public function testLimitingSuccess()
    {
        $this->promocode->expects($this->any())->method('getLimiting')->willReturn(100);
        $this->promocode->expects($this->any())->method('getUsed')->willReturn(99);
        $this->promocodeFilter = new PromocodeFilter($this->promocode, $this->customer);
        $this->assertInstanceOf(PromocodeFilterInterface::class, $this->promocodeFilter->limiting());
        $this->assertTrue($this->promocodeFilter->getResult());
    }

    public function testLimitingFailure()
    {
        $this->promocode->expects($this->any())->method('getLimiting')->willReturn(100);
        $this->promocode->expects($this->any())->method('getUsed')->willReturn(100);
        $this->promocodeFilter = new PromocodeFilter($this->promocode, $this->customer);
        $this->assertInstanceOf(PromocodeFilterInterface::class, $this->promocodeFilter->limiting());
        $this->assertFalse($this->promocodeFilter->getResult());
    }
}
