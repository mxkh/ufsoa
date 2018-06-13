<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\CommonBundle\Entity\Language;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopLanguage;

/**
 * Class ShopLanguageTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity
 */
class ShopLanguageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ShopLanguage
     */
    private $shopLanguage;

    /**
     *{@inheritdoc}
     */
    protected function setUp()
    {
        $this->shopLanguage = new ShopLanguage();
    }

    public function testShop()
    {
        $shop = new Shop();
        $this->assertInstanceOf(ShopLanguage::class, $this->shopLanguage->setShop($shop));
        $this->assertInstanceOf(Shop::class, $this->shopLanguage->getShop());
        $this->shopLanguage->setShop($shop);
    }

    public function testCurrency()
    {
        $language = new Language();
        $this->assertInstanceOf(ShopLanguage::class, $this->shopLanguage->setLanguage($language));
        $this->assertInstanceOf(Language::class, $this->shopLanguage->getLanguage());
        $this->shopLanguage->setLanguage($language);
    }

    public function testIsDefault()
    {
        $this->assertInstanceOf(ShopLanguage::class, $this->shopLanguage->setIsDefault(true));
        $this->assertEquals(true, $this->shopLanguage->getIsDefault());
        $this->assertInternalType('bool', $this->shopLanguage->getIsDefault());
        $this->shopLanguage->setIsDefault(false);
    }

    public function testShopDefaultables()
    {
        /** @var Shop|\PHPUnit_Framework_MockObject_MockObject $order */
        $shop = $this->createMock(Shop::class);
        $shop
            ->expects($this->once())
            ->method('getLanguages');

        $ordersReflect = new \ReflectionProperty(ShopLanguage::class, 'shop');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->shopLanguage, $shop);

        $this->shopLanguage->setShop($shop);

        $this->assertInstanceOf(Collection::class, $this->shopLanguage->getShopDefaultables());
    }
}
