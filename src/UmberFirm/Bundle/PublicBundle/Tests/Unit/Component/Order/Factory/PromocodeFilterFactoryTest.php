<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Order\Factory;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\Promocode;
use UmberFirm\Bundle\PublicBundle\Component\Order\Factory\PromocodeFilterFactory;
use UmberFirm\Bundle\PublicBundle\Component\Order\Filter\PromocodeFilterInterface;

/**
 * Class PromocodeFilterFactoryTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Order\Factory
 */
class PromocodeFilterFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|Customer $customer */
    private $customer;

    /** @var \PHPUnit_Framework_MockObject_MockObject|Promocode $promocode */
    private $promocode;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->customer = $this->createMock(Customer::class);
        $this->promocode = $this->createMock(Promocode::class);
    }

    public function testCreateFilter()
    {
        $factory = new PromocodeFilterFactory();
        $this->assertInstanceOf(PromocodeFilterInterface::class, $factory->createFilter($this->promocode, $this->customer));
        $this->assertInstanceOf(PromocodeFilterInterface::class, $factory->createFilter($this->promocode, null));
    }
}
