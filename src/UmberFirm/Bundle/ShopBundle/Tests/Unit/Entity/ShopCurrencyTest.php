<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\CommonBundle\Entity\Currency;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopCurrency;

/**
 * Class ShopCurrencyTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity
 */
class ShopCurrencyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ShopCurrency
     */
    private $shopCurrency;

    /**
     *{@inheritdoc}
     */
    protected function setUp()
    {
        $this->shopCurrency = new ShopCurrency();
    }

    public function testShop()
    {
        $shop = new Shop();
        $this->assertInstanceOf(ShopCurrency::class, $this->shopCurrency->setShop($shop));
        $this->assertInstanceOf(Shop::class, $this->shopCurrency->getShop());
        $this->shopCurrency->setShop($shop);
    }

    public function testCurrency()
    {
        $currency = new Currency();
        $this->assertInstanceOf(ShopCurrency::class, $this->shopCurrency->setCurrency($currency));
        $this->assertInstanceOf(Currency::class, $this->shopCurrency->getCurrency());
        $this->shopCurrency->setCurrency($currency);
    }

    public function testIsDefault()
    {
        $this->assertInstanceOf(ShopCurrency::class, $this->shopCurrency->setIsDefault(true));
        $this->assertEquals(true, $this->shopCurrency->getIsDefault());
        $this->assertInternalType('bool', $this->shopCurrency->getIsDefault());
        $this->shopCurrency->setIsDefault(false);
    }

    public function testShopDefaultables()
    {
        /** @var Shop|\PHPUnit_Framework_MockObject_MockObject $order */
        $shop = $this->createMock(Shop::class);
        $shop
            ->expects($this->once())
            ->method('getCurrencies');

        $ordersReflect = new \ReflectionProperty(ShopCurrency::class, 'shop');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->shopCurrency, $shop);

        $this->shopCurrency->setShop($shop);

        $this->assertInstanceOf(Collection::class, $this->shopCurrency->getShopDefaultables());
    }
}
