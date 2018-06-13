<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PaymentBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\ShopBundle\Entity\ShopPayment;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPaymentSettings;

/**
 * Class PaymentSettingsTest
 *
 * @package UmberFirm\Bundle\PaymentBundle\Tests\Unit\Entity
 */
class ShopPaymentSettingsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ShopPaymentSettings
     */
    private $settings;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->settings = new ShopPaymentSettings();
    }

    public function testPublicKey()
    {
        $this->assertInstanceOf(ShopPaymentSettings::class, $this->settings->setPublicKey(null));
        $this->assertInternalType('string', $this->settings->getPublicKey());
        $this->assertInstanceOf(ShopPaymentSettings::class, $this->settings->setPublicKey('string'));
        $this->assertEquals('string', $this->settings->getPublicKey());
    }

    /**
     * @expectedException \TypeError
     */
    public function testPublicKeyTypeError()
    {
        $this->settings->setPublicKey(123);
    }

    public function testPrivateKey()
    {
        $this->assertInstanceOf(ShopPaymentSettings::class, $this->settings->setPrivateKey(null));
        $this->assertInternalType('string', $this->settings->getPrivateKey());
        $this->assertInstanceOf(ShopPaymentSettings::class, $this->settings->setPrivateKey('string'));
        $this->assertEquals('string', $this->settings->getPrivateKey());
    }

    /**
     * @expectedException \TypeError
     */
    public function testPrivateKeyTypeError()
    {
        $this->settings->setPrivateKey(123);
    }

    public function testReturnUrl()
    {
        $this->assertInstanceOf(ShopPaymentSettings::class, $this->settings->setReturnUrl(null));
        $this->assertInternalType('string', $this->settings->getReturnUrl());
        $this->assertInstanceOf(ShopPaymentSettings::class, $this->settings->setReturnUrl('string'));
        $this->assertEquals('string', $this->settings->getReturnUrl());
    }

    /**
     * @expectedException \TypeError
     */
    public function testReturnUrlTypeError()
    {
        $this->settings->setReturnUrl(123);
    }

    public function testMerchantAuthType()
    {
        $this->assertInstanceOf(ShopPaymentSettings::class, $this->settings->setMerchantAuthType(null));
        $this->assertInternalType('string', $this->settings->getMerchantAuthType());
        $this->assertInstanceOf(ShopPaymentSettings::class, $this->settings->setMerchantAuthType('string'));
        $this->assertEquals('string', $this->settings->getMerchantAuthType());
    }

    /**
     * @expectedException \TypeError
     */
    public function testMerchantAuthTypeTypeError()
    {
        $this->settings->setMerchantAuthType(123);
    }

    public function testMerchantTransactionType()
    {
        $this->assertInstanceOf(ShopPaymentSettings::class, $this->settings->setMerchantTransactionType(null));
        $this->assertInternalType('string', $this->settings->getMerchantTransactionType());
        $this->assertInstanceOf(ShopPaymentSettings::class, $this->settings->setMerchantTransactionType('string'));
        $this->assertEquals('string', $this->settings->getMerchantTransactionType());
    }

    /**
     * @expectedException \TypeError
     */
    public function testMerchantTransactionTypeTypeError()
    {
        $this->settings->setMerchantTransactionType(123);
    }

    public function testSandboxType()
    {
        $this->assertInstanceOf(ShopPaymentSettings::class, $this->settings->setTestMode(null));
        $this->assertInternalType('bool', $this->settings->isTestMode());
        $this->assertInstanceOf(ShopPaymentSettings::class, $this->settings->setTestMode(true));
        $this->assertEquals(true, $this->settings->isTestMode());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSandboxError()
    {
        $this->settings->setTestMode(123);
    }

    public function testShopPayment()
    {
        $this->assertInstanceOf(ShopPaymentSettings::class, $this->settings->setShopPayment(null));
        $this->assertEquals(null, $this->settings->getShopPayment());
        $this->assertInstanceOf(ShopPaymentSettings::class, $this->settings->setShopPayment(new ShopPayment()));
        $this->assertInstanceOf(ShopPayment::class, $this->settings->getShopPayment());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShopPaymentTypeError()
    {
        $this->settings->setShopPayment(123);
    }
}
