<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PaymentBundle\Tests\Unit\Component\Factory;

use Symfony\Component\Routing\RouterInterface;
use UmberFirm\Bundle\PaymentBundle\Component\Adapter\PaymentInterface;
use UmberFirm\Bundle\PaymentBundle\Component\Factory\WayForPayFactory;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPaymentSettings;

/**
 * Class WayForPayFactoryTest
 *
 * @package UmberFirm\Bundle\PaymentBundle\Tests\Unit\Component\Factory
 */
class WayForPayFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RouterInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $router;

    /**
     * @var ShopPaymentSettings|\PHPUnit_Framework_MockObject_MockObject
     */
    private $shopPaymentSettings;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->router = $this->createMock(RouterInterface::class);
        $this->shopPaymentSettings = $this->createMock(ShopPaymentSettings::class);
    }

    public function testCreatePayment()
    {
        $wayForPayFactory = new WayForPayFactory($this->router);

        $this->shopPaymentSettings
            ->expects($this->once())
            ->method('getPublicKey')
            ->willReturn('publicKey');

        $this->shopPaymentSettings
            ->expects($this->once())
            ->method('getPrivateKey')
            ->willReturn('privateKey');

        $this->assertInstanceOf(PaymentInterface::class, $wayForPayFactory->createPayment($this->shopPaymentSettings));
    }
}
