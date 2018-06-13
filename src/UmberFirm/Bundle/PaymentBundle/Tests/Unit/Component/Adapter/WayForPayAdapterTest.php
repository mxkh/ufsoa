<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PaymentBundle\Tests\Unit\Component\Adapter;

use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;
use UmberFirm\Bundle\PaymentBundle\Component\Adapter\WayForPayAdapter;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPaymentSettings;

/**
 * Class WayForPayAdapterTest
 *
 * @package UmberFirm\Bundle\PaymentBundle\Tests\Unit\Component\Adapter
 */
class WayForPayAdapterTest extends \PHPUnit_Framework_TestCase
{
    /** @var ShopPaymentSettings|\PHPUnit_Framework_MockObject_MockObject */
    private $paymentSettings;

    /** @var \WayForPay|\PHPUnit_Framework_MockObject_MockObject */
    private $wayForPay;

    /** @var RouterInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $router;

    protected function setUp()
    {
        $this->wayForPay = $this->createMock(\WayForPay::class);
        $this->paymentSettings = $this->createMock(ShopPaymentSettings::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->router
            ->expects($this->once())
            ->method('generate')
            ->willReturn('generatedUrl');
    }

    public function testGeneratePaymentUrl()
    {
        $this->wayForPay
            ->expects($this->once())
            ->method('generatePurchaseUrl')
            ->willReturn('secure.wayforpay.com');

        $wayForPayPaymentService = new WayForPayAdapter($this->wayForPay, $this->paymentSettings, $this->router);

        $this->assertInternalType('string', $wayForPayPaymentService->generatePaymentUrl([]));
    }

    public function testHoldCompletion()
    {
        $this->wayForPay
            ->expects($this->once())
            ->method('settle');

        $wayForPayPaymentService = new WayForPayAdapter($this->wayForPay, $this->paymentSettings, $this->router);

        $this->assertInstanceOf(JsonResponse::class, $wayForPayPaymentService->holdCompletion([]));
    }

    public function testCheckPaymentStatus()
    {
        $this->wayForPay
            ->expects($this->once())
            ->method('checkStatus');

        $wayForPayPaymentService = new WayForPayAdapter($this->wayForPay, $this->paymentSettings, $this->router);

        $this->assertInstanceOf(JsonResponse::class, $wayForPayPaymentService->checkPaymentStatus([]));
    }

    public function testRefundPayment()
    {
        $this->wayForPay
            ->expects($this->once())
            ->method('refund');

        $wayForPayPaymentService = new WayForPayAdapter($this->wayForPay, $this->paymentSettings, $this->router);

        $this->assertInstanceOf(JsonResponse::class, $wayForPayPaymentService->refundPayment([]));
    }
}
