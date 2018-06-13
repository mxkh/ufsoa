<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Unit\Event\Order;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\FastOrder;
use UmberFirm\Bundle\PublicBundle\Event\Order\FastOrderEvent;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class FastOrderEventTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Unit\Event\Order
 */
class FastOrderEventTest extends \PHPUnit_Framework_TestCase
{
    /** @var FastOrder|\PHPUnit_Framework_MockObject_MockObject */
    protected $fastOrder;

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

    public function testGetFastOrder()
    {
        $fastOrderEvent = new FastOrderEvent($this->fastOrder, $this->shop, $this->customer);
        $fastOrder = $fastOrderEvent->getFastOrder();

        $this->assertInstanceOf(FastOrder::class, $fastOrder);
        $this->assertEquals($fastOrder, $this->fastOrder);
    }

    public function testGetShop()
    {
        $fastOrderEvent = new FastOrderEvent($this->fastOrder, $this->shop, $this->customer);
        $shop = $fastOrderEvent->getShop();

        $this->assertInstanceOf(Shop::class, $shop);
        $this->assertEquals($shop, $this->shop);
    }

    public function testGetCustomer()
    {
        $fastOrderEvent = new FastOrderEvent($this->fastOrder, $this->shop, $this->customer);
        $customer = $fastOrderEvent->getCustomer();

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals($customer, $this->customer);
    }

}
