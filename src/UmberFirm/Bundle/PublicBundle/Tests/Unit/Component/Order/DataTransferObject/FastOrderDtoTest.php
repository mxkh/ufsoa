<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Order\DataTransferObject;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\FastOrder;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\PublicBundle\Component\Order\DataTransferObject\FastOrderDTO;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class FastOrderDtoTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Order\DataTransferObject
 */
class FastOrderDtoTest extends \PHPUnit_Framework_TestCase
{
    /** @var  FastOrder|\PHPUnit_Framework_MockObject_MockObject */
    private $fastOrder;

    /** @var  Shop|\PHPUnit_Framework_MockObject_MockObject */
    private $shop;

    /** @var  Customer|\PHPUnit_Framework_MockObject_MockObject */
    private $customer;

    /** @var  ProductVariant|\PHPUnit_Framework_MockObject_MockObject */
    private $productVariant;

    /** @var  Uuid|\PHPUnit_Framework_MockObject_MockObject */
    private $uuid;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->fastOrder = $this->createMock(FastOrder::class);
        $this->shop = $this->createMock(Shop::class);
        $this->customer = $this->createMock(Customer::class);
        $this->productVariant = $this->createMock(ProductVariant::class);
        $this->uuid = $this->createMock(UuidInterface::class);
    }

    public function testFastOrderDTO()
    {
        $this->uuid->expects($this->any())->method('toString')->willReturn(Uuid::uuid4());
        $this->productVariant->expects($this->once())->method('getId')->willReturn($this->uuid);
        $this->customer->expects($this->once())->method('getId')->willReturn($this->uuid);
        $this->shop->expects($this->once())->method('getId')->willReturn($this->uuid);
        $this->fastOrder->expects($this->once())->method('getProductVariant')->willReturn($this->productVariant);

        $fastOrderDTO = new FastOrderDTO($this->fastOrder, $this->shop, $this->customer);
        $this->assertInternalType('string', $fastOrderDTO->getProductVariant());
        $this->assertInternalType('string', $fastOrderDTO->getShop());
        $this->assertInternalType('string', $fastOrderDTO->getCustomer());
        $this->assertInternalType('string', $fastOrderDTO->getPhone());
    }
}
