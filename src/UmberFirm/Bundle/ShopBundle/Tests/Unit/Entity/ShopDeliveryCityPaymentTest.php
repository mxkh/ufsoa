<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDeliveryCity;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDeliveryCityPayment;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPayment;

/**
 * Class ShopDeliveryCityPaymentTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity
 */
class ShopDeliveryCityPaymentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ShopDeliveryCityPayment
     */
    private $shopDeliveryCityPayment;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->shopDeliveryCityPayment = new ShopDeliveryCityPayment();
    }

    public function testShopDeliveryCity()
    {
        $this->shopDeliveryCityPayment->setShopDeliveryCity(null);
        $this->assertNull($this->shopDeliveryCityPayment->getShopDeliveryCity());
        $this->assertInstanceOf(ShopDeliveryCityPayment::class, $this->shopDeliveryCityPayment->setShopDeliveryCity(new ShopDeliveryCity()));
        $this->assertInstanceOf(ShopDeliveryCity::class, $this->shopDeliveryCityPayment->getShopDeliveryCity());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShopDeliveryCityTypeError()
    {
        $this->shopDeliveryCityPayment->setShopDeliveryCity(new \stdClass());
    }

    public function testShop()
    {
        $this->shopDeliveryCityPayment->setShop(null);
        $this->assertNull($this->shopDeliveryCityPayment->getShop());
        $this->assertInstanceOf(ShopDeliveryCityPayment::class, $this->shopDeliveryCityPayment->setShop(new Shop()));
        $this->assertInstanceOf(Shop::class, $this->shopDeliveryCityPayment->getShop());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShopTypeError()
    {
        $this->shopDeliveryCityPayment->setShop(new \stdClass());
    }

    public function testShopPayment()
    {
        $this->shopDeliveryCityPayment->setShopPayment(null);
        $this->assertNull($this->shopDeliveryCityPayment->getShopPayment());
        $this->assertInstanceOf(ShopDeliveryCityPayment::class, $this->shopDeliveryCityPayment->setShopPayment(new ShopPayment()));
        $this->assertInstanceOf(ShopPayment::class, $this->shopDeliveryCityPayment->getShopPayment());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShopPaymentTypeError()
    {
        $this->shopDeliveryCityPayment->setShopPayment(new \stdClass());
    }
}
