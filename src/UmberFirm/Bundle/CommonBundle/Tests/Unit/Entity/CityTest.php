<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\CommonBundle\Entity\City;

/**
 * Class CityTest
 *
 * @package UmberFirm\Bundle\CommonBundle\Tests\Unit\Entity
 */
class CityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var City
     */
    private $city;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->city = new City();
    }

    public function testName()
    {
        $this->assertEmpty($this->city->getName());
        $this->assertInstanceOf(City::class, $this->city->setName('Kyiv'));
        $this->assertEquals('Kyiv', $this->city->getName());
    }
}
