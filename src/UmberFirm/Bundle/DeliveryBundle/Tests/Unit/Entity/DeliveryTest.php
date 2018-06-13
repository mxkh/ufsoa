<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\DeliveryBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\DeliveryBundle\Entity\Delivery;
use UmberFirm\Bundle\DeliveryBundle\Entity\DeliveryGroup;

/**
 * Class DeliveryTest
 *
 * @package UmberFirm\Bundle\DeliveryBundle\Tests\Unit\Entity
 */
class DeliveryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Delivery
     */
    private $delivery;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->delivery = new Delivery();
    }

    public function testCode()
    {
        $this->delivery->setCode(null);
        $this->assertInternalType('string', $this->delivery->getCode());
        $this->assertInstanceOf(Delivery::class, $this->delivery->setCode('string'));
        $this->assertEquals('string', $this->delivery->getCode());
    }

    /**
     * @expectedException \TypeError
     */
    public function testCodeTypeError()
    {
        $this->delivery->setCode(123);
    }

    public function testGroup()
    {
        $this->delivery->setGroup(null);
        $this->assertNull($this->delivery->getGroup());
        $this->assertInstanceOf(Delivery::class, $this->delivery->setGroup(new DeliveryGroup()));
        $this->assertInstanceOf(DeliveryGroup::class, $this->delivery->getGroup());
    }

    /**
     * @expectedException \TypeError
     */
    public function testGroupTypeError()
    {
        $this->delivery->setGroup(new \stdClass());
    }

    public function testName()
    {
        /** @var string $locale */
        $locale = $this->delivery->getDefaultLocale();
        $this->assertInstanceOf(Delivery::class, $this->delivery->setName('name', $locale));
        $this->assertEquals('name', $this->delivery->getName());
    }

    /**
     * @expectedException \TypeError
     */
    public function testNameTypeError()
    {
        /** @var string $locale */
        $locale = $this->delivery->getDefaultLocale();
        $this->delivery->setName(123, $locale);
    }

    public function testDescription()
    {
        /** @var string $locale */
        $locale = $this->delivery->getDefaultLocale();
        $this->assertInstanceOf(Delivery::class, $this->delivery->setDescription('description', $locale));
        $this->assertEquals('description', $this->delivery->getDescription());
    }

    /**
     * @expectedException \TypeError
     */
    public function testDescriptionTypeError()
    {
        /** @var string $locale */
        $locale = $this->delivery->getDefaultLocale();
        $this->delivery->setDescription(123, $locale);
    }
}
