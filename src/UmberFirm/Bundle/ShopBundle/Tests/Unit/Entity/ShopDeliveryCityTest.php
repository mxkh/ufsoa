<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\CommonBundle\Entity\City;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDelivery;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDeliveryCity;

/**
 * Class ShopDeliveryCityTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity
 */
class ShopDeliveryCityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ShopDeliveryCity
     */
    private $shopDeliveryCity;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->shopDeliveryCity = new ShopDeliveryCity();
    }

    public function testShopDelivery()
    {
        $this->shopDeliveryCity->setShopDelivery(null);
        $this->assertNull($this->shopDeliveryCity->getShopDelivery());
        $this->assertInstanceOf(ShopDeliveryCity::class, $this->shopDeliveryCity->setShopDelivery(new ShopDelivery()));
        $this->assertInstanceOf(ShopDelivery::class, $this->shopDeliveryCity->getShopDelivery());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShopDeliveryTypeError()
    {
        $this->shopDeliveryCity->setShopDelivery(new \stdClass());
    }

    public function testShop()
    {
        $this->shopDeliveryCity->setShop(null);
        $this->assertNull($this->shopDeliveryCity->getShop());
        $this->assertInstanceOf(ShopDeliveryCity::class, $this->shopDeliveryCity->setShop(new Shop()));
        $this->assertInstanceOf(Shop::class, $this->shopDeliveryCity->getShop());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShopTypeError()
    {
        $this->shopDeliveryCity->setShop(new \stdClass());
    }

    public function testCity()
    {
        $this->shopDeliveryCity->setCity(null);
        $this->assertNull($this->shopDeliveryCity->getCity());
        $this->assertInstanceOf(ShopDeliveryCity::class, $this->shopDeliveryCity->setCity(new City()));
        $this->assertInstanceOf(City::class, $this->shopDeliveryCity->getCity());
    }

    /**
     * @expectedException \TypeError
     */
    public function testCityTypeError()
    {
        $this->shopDeliveryCity->setCity(new \stdClass());
    }
}
