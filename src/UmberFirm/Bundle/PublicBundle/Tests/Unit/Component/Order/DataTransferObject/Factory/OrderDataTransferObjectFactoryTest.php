<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Order\DataTransferObject\Factory;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\FastOrder;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\PublicBundle\Component\Order\DataTransferObject\Factory\OrderDataTransferObjectFactory;
use UmberFirm\Bundle\PublicBundle\Component\Order\DataTransferObject\FastOrderDTOInterface;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class OrderDataTransferObjectFactoryTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Order\DataTransferObject\Factory
 */
class OrderDataTransferObjectFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var  FastOrder|\PHPUnit_Framework_MockObject_MockObject */
    private $fastOrder;

    /** @var  Shop|\PHPUnit_Framework_MockObject_MockObject */
    private $shop;

    /** @var  Customer|\PHPUnit_Framework_MockObject_MockObject */
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

    public function testCreateFastOrderDTO()
    {
        $uuid = $this->createMock(UuidInterface::class);
        $uuid->expects($this->any())->method('toString')->willReturn(Uuid::uuid4());

        $productVariant = $this->createMock(ProductVariant::class);
        $productVariant->expects($this->once())->method('getId')->willReturn($uuid);

        $this->customer->expects($this->once())->method('getId')->willReturn($uuid);
        $this->shop->expects($this->once())->method('getId')->willReturn($uuid);

        $this->fastOrder->expects($this->once())->method('getProductVariant')->willReturn($productVariant);

        $orderDataTransferObjectFactory = new OrderDataTransferObjectFactory();
        $this->assertInstanceOf(
            FastOrderDTOInterface::class,
            $orderDataTransferObjectFactory->createFastOrderDTO($this->fastOrder, $this->shop, $this->customer)
        );
    }

}
