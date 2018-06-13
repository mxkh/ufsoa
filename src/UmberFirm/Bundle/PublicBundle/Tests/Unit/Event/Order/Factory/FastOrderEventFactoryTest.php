<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Unit\Event\Order\Factory;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\FastOrder;
use UmberFirm\Bundle\PublicBundle\Event\Order\Factory\FastOrderEventFactory;
use UmberFirm\Bundle\PublicBundle\Event\Order\FastOrderEventInterface;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class FastOrderEventFactoryTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Unit\Event\Order\Factory
 */
class FastOrderEventFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var FastOrder|\PHPUnit_Framework_MockObject_MockObject */
    private $fastOrder;

    /** @var Shop|\PHPUnit_Framework_MockObject_MockObject */
    private $shop;

    /** @var Customer|\PHPUnit_Framework_MockObject_MockObject */
    private $customer;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->fastOrder = $this->createMock(FastOrder::class);
        $this->shop = $this->createMock(Shop::class);
        $this->customer = $this->createMock(Customer::class);
    }

    public function testFastOrderEvent()
    {
        $fastOrderEventFactory = new FastOrderEventFactory();
        $fastOrderEvent = $fastOrderEventFactory->createFastOrderEvent($this->fastOrder, $this->shop, $this->customer);

        $this->assertInstanceOf(FastOrderEventInterface::class, $fastOrderEvent);
    }

}
