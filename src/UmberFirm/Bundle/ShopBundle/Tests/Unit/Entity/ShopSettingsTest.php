<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\ShopBundle\Entity\SettingsAttribute;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopSettings;

/**
 * Class ShopSettingsTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity
 */
class ShopSettingsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ShopSettings
     */
    private $shopSettings;

    /**
     *{@inheritdoc}
     */
    protected function setUp()
    {
        $this->shopSettings = new ShopSettings();
    }

    public function testShop()
    {
        $shop = new Shop();
        $this->assertInstanceOf(ShopSettings::class, $this->shopSettings->setShop($shop));
        $this->assertInstanceOf(Shop::class, $this->shopSettings->getShop());
        $this->assertEquals($shop, $this->shopSettings->getShop());
        $this->shopSettings->setShop($shop);
    }

    public function testAttribute()
    {
        $attribute = new SettingsAttribute();
        $this->assertInstanceOf(ShopSettings::class, $this->shopSettings->setAttribute($attribute));
        $this->assertInstanceOf(SettingsAttribute::class, $this->shopSettings->getAttribute());
        $this->assertEquals($attribute, $this->shopSettings->getAttribute());
        $this->shopSettings->setAttribute($attribute);
    }

    public function testValue()
    {
        $this->assertInstanceOf(ShopSettings::class, $this->shopSettings->setValue("100"));
        $this->assertInternalType('string', $this->shopSettings->getValue());
        $this->assertEquals("100", $this->shopSettings->getValue());
        $this->shopSettings->setValue("100");
    }

    /**
     * @expectedException \TypeError
     */
    public function testWrongValue()
    {
        $this->shopSettings->setValue(123);
    }
}
