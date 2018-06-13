<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\DeliveryBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\CommonBundle\Entity\City;
use UmberFirm\Bundle\DeliveryBundle\Entity\CityDelivery;
use UmberFirm\Bundle\DeliveryBundle\Entity\DeliveryGroup;

/**
 * Class CityDeliveryTest
 *
 * @package UmberFirm\Bundle\DeliveryBundle\Tests\Unit\Entity
 */
class CityDeliveryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CityDelivery
     */
    private $cityDelivery;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->cityDelivery = new CityDelivery();
    }

    public function testCity()
    {
        $this->cityDelivery->setCity(null);
        $this->assertNull($this->cityDelivery->getCity());
        $this->assertInstanceOf(CityDelivery::class, $this->cityDelivery->setCity(new City()));
        $this->assertInstanceOf(City::class, $this->cityDelivery->getCity());
    }

    /**
     * @expectedException \TypeError
     */
    public function testCityTypeError()
    {
        $this->cityDelivery->setCity(new \stdClass());
    }

    public function testDeliveryGroup()
    {
        $this->cityDelivery->setDeliveryGroup(null);
        $this->assertNull($this->cityDelivery->getDeliveryGroup());
        $this->assertInstanceOf(CityDelivery::class, $this->cityDelivery->setDeliveryGroup(new DeliveryGroup()));
        $this->assertInstanceOf(DeliveryGroup::class, $this->cityDelivery->getDeliveryGroup());
    }

    /**
     * @expectedException \TypeError
     */
    public function testDeliveryGroupTypeError()
    {
        $this->cityDelivery->setCity(new \stdClass());
    }
}
