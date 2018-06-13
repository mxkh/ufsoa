<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\PaymentBundle\Entity\Payment;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPayment;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPaymentSettings;

/**
 * Class ShopPaymentTest
 *
 * @package UmberFirm\Bundle\PaymentBundle\Tests\Unit\Entity
 */
class ShopPaymentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ShopPayment
     */
    private $shopPayment;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->shopPayment = new ShopPayment();
    }

    public function testShop()
    {
        $this->assertInstanceOf(ShopPayment::class, $this->shopPayment->setShop(null));
        $this->assertEquals(null, $this->shopPayment->getShop());
        $this->assertInstanceOf(ShopPayment::class, $this->shopPayment->setShop(new Shop()));
        $this->assertInstanceOf(Shop::class, $this->shopPayment->getShop());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShopTypeError()
    {
        $this->shopPayment->setShop(123);
    }

    public function testSettings()
    {
        $this->assertEquals(null, $this->shopPayment->getSettings());
        $this->assertInstanceOf(ShopPayment::class, $this->shopPayment->setSettings(new ShopPaymentSettings()));
        $this->assertInstanceOf(ShopPaymentSettings::class, $this->shopPayment->getSettings());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSettingsTypeError()
    {
        $this->shopPayment->setSettings(null);
    }

    public function testPayment()
    {
        $this->assertInstanceOf(ShopPayment::class, $this->shopPayment->setPayment(null));
        $this->assertEquals(null, $this->shopPayment->getPayment());
        $this->assertInstanceOf(ShopPayment::class, $this->shopPayment->setPayment(new Payment()));
        $this->assertInstanceOf(Payment::class, $this->shopPayment->getPayment());
    }

    /**
     * @expectedException \TypeError
     */
    public function testPaymentTypeError()
    {
        $this->shopPayment->setPayment(123);
    }
}

