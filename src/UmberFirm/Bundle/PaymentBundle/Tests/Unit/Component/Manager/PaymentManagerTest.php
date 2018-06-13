<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PaymentBundle\Tests\Unit\Component\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use UmberFirm\Bundle\CommonBundle\Entity\Currency;
use UmberFirm\Bundle\OrderBundle\Entity\Order;
use UmberFirm\Bundle\OrderBundle\Entity\OrderItem;
use UmberFirm\Bundle\PaymentBundle\Component\Adapter\PaymentInterface;
use UmberFirm\Bundle\PaymentBundle\Component\Factory\AbstractPaymentFactory;
use UmberFirm\Bundle\PaymentBundle\Component\Factory\PaymentFactoryManagerInterface;
use UmberFirm\Bundle\PaymentBundle\Component\Manager\PaymentManager;
use UmberFirm\Bundle\PaymentBundle\Entity\Payment;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\ShopBundle\Entity\ShopCurrency;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPayment;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPaymentSettings;

/**
 * Class PaymentManagerTest
 *
 * @package UmberFirm\Bundle\PaymentBundle\Tests\Unit\Component\Manager
 */
class PaymentManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PaymentFactoryManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $paymentFactoryManager;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->paymentFactoryManager = $this->createMock(PaymentFactoryManagerInterface::class);
    }

    public function testGetPayment()
    {
        $payment = $this->createMock(PaymentInterface::class);
        $shopPayment = $this->createMock(ShopPayment::class);
        $shopPayment
            ->expects($this->once())
            ->method('getPayment')
            ->willReturn(new Payment());

        $shopPayment
            ->expects($this->once())
            ->method('getSettings')
            ->willReturn(new ShopPaymentSettings());

        $paymentFactory = $this->createMock(AbstractPaymentFactory::class);
        $paymentFactory
            ->expects($this->once())
            ->method('createPayment')
            ->willReturn($payment);

        $this->paymentFactoryManager
            ->expects($this->once())
            ->method('getFactory')
            ->willReturn($paymentFactory);

        $paymentManager = new PaymentManager($this->paymentFactoryManager);
        $this->assertInstanceOf(PaymentInterface::class, $paymentManager->getPayment($shopPayment));
    }

    public function testGetPaymentNull()
    {
        $shopPayment = $this->createMock(ShopPayment::class);
        $shopPayment
            ->expects($this->once())
            ->method('getPayment')
            ->willReturn(new Payment());

        $this->paymentFactoryManager
            ->expects($this->once())
            ->method('getFactory')
            ->willReturn(null);

        $paymentManager = new PaymentManager($this->paymentFactoryManager);
        $this->assertNull($paymentManager->getPayment($shopPayment));
    }

    public function testGeneratePaymentUrlNull()
    {
        $shopPayment = $this->createMock(ShopPayment::class);
        $shopPayment
            ->expects($this->once())
            ->method('getPayment')
            ->willReturn(new Payment());

        $this->paymentFactoryManager
            ->expects($this->once())
            ->method('getFactory')
            ->willReturn(null);

        $order = $this->createMock(Order::class);

        $paymentManager = new PaymentManager($this->paymentFactoryManager);
        $this->assertNull($paymentManager->generatePaymentUrl($shopPayment, $order));
    }

    public function testGeneratePaymentUrl()
    {
        $payment = $this->createMock(PaymentInterface::class);
        $shopPayment = $this->createMock(ShopPayment::class);
        $shopPayment->expects($this->once())->method('getPayment')->willReturn(new Payment());

        $shopPayment->expects($this->once())->method('getSettings')->willReturn(new ShopPaymentSettings());

        $paymentFactory = $this->createMock(AbstractPaymentFactory::class);
        $paymentFactory->expects($this->once())->method('createPayment')->willReturn($payment);

        $this->paymentFactoryManager->expects($this->once())->method('getFactory')->willReturn($paymentFactory);

        $order = $this->createMock(Order::class);
        $product = $this->createMock(Product::class);
        $shopCurrency = $this->createMock(ShopCurrency::class);
        $shopCurrency->expects($this->once())->method('getCurrency')->willReturn(new Currency());
        $productVariant = $this->createMock(ProductVariant::class);
        $productVariant->expects($this->once())->method('getProduct')->willReturn($product);
        $orderItem = $this->createMock(OrderItem::class);
        $orderItem->expects($this->any())->method('getProductVariant')->willReturn($productVariant);
        $order->expects($this->once())->method('getOrderItems')->willReturn(new ArrayCollection([$orderItem]));
        $order->expects($this->once())->method('getShopCurrency')->willReturn($shopCurrency);

        $paymentManager = new PaymentManager($this->paymentFactoryManager);
        $this->assertInternalType('string', $paymentManager->generatePaymentUrl($shopPayment, $order));
    }
}
