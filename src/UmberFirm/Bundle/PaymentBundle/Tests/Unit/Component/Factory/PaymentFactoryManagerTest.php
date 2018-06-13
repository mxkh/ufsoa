<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PaymentBundle\Tests\Unit\Component\Adapter\Factory;

use Symfony\Component\Routing\RouterInterface;
use UmberFirm\Bundle\PaymentBundle\Component\Adapter\WayForPayAdapter;
use UmberFirm\Bundle\PaymentBundle\Component\Factory\AbstractPaymentFactory;
use UmberFirm\Bundle\PaymentBundle\Component\Factory\PaymentFactoryManager;

/**
 * Class PaymentFactoryManagerTest
 *
 * @package UmberFirm\Bundle\PaymentBundle\Tests\Unit\Component\Adapter\Factory
 */
class PaymentFactoryManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RouterInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $router;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->router = $this->createMock(RouterInterface::class);
    }

    public function testCreatePaymentService()
    {
        $paymentFactoryManager = new PaymentFactoryManager($this->router);
        $this->assertInstanceOf(
            AbstractPaymentFactory::class,
            $paymentFactoryManager->getFactory(WayForPayAdapter::NAME)
        );
    }

    public function testCreatePaymentServiceWrongCode()
    {
        $paymentFactoryManager = new PaymentFactoryManager($this->router);
        $this->assertNull($paymentFactoryManager->getFactory('wrongPay'));
    }
}
