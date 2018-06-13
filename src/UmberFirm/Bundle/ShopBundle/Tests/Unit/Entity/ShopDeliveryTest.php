<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\DeliveryBundle\Entity\Delivery;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDelivery;

/**
 * Class ShopDeliveryTest
 *
 * @package UmberFirm\Bundle\DeliveryBundle\Tests\Unit\Entity
 */
class ShopDeliveryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ShopDelivery
     */
    private $shopDelivery;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->shopDelivery = new ShopDelivery();
    }

    public function testDelivery()
    {
        $this->shopDelivery->setDelivery(null);
        $this->assertNull($this->shopDelivery->getDelivery());
        $this->assertInstanceOf(ShopDelivery::class, $this->shopDelivery->setDelivery(new Delivery()));
        $this->assertInstanceOf(Delivery::class, $this->shopDelivery->getDelivery());
    }

    /**
     * @expectedException \TypeError
     */
    public function testDeliveryTypeError()
    {
        $this->shopDelivery->setDelivery(new \stdClass());
    }

    public function testShop()
    {
        $this->shopDelivery->setShop(null);
        $this->assertNull($this->shopDelivery->getShop());
        $this->assertInstanceOf(ShopDelivery::class, $this->shopDelivery->setShop(new Shop()));
        $this->assertInstanceOf(Shop::class, $this->shopDelivery->getShop());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShopTypeError()
    {
        $this->shopDelivery->setShop(new \stdClass());
    }
}
