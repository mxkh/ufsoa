<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\DeliveryBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\DeliveryBundle\Entity\DeliveryGroup;

/**
 * Class DeliveryGroupTest
 *
 * @package UmberFirm\Bundle\DeliveryBundle\Tests\Unit\Entity
 */
class DeliveryGroupTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DeliveryGroup
     */
    private $deliveryGroup;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->deliveryGroup = new DeliveryGroup();
    }

    public function testCode()
    {
        $this->deliveryGroup->setCode(null);
        $this->assertInternalType('string', $this->deliveryGroup->getCode());
        $this->assertInstanceOf(DeliveryGroup::class, $this->deliveryGroup->setCode('string'));
        $this->assertEquals('string', $this->deliveryGroup->getCode());
    }

    /**
     * @expectedException \TypeError
     */
    public function testCodeTypeError()
    {
        $this->deliveryGroup->setCode(123);
    }

    public function testName()
    {
        /** @var string $locale */
        $locale = $this->deliveryGroup->getDefaultLocale();
        $this->assertInstanceOf(DeliveryGroup::class, $this->deliveryGroup->setName('name', $locale));
        $this->assertEquals('name', $this->deliveryGroup->getName());
    }

    /**
     * @expectedException \TypeError
     */
    public function testNameTypeError()
    {
        /** @var string $locale */
        $locale = $this->deliveryGroup->getDefaultLocale();
        $this->deliveryGroup->setName(123, $locale);
    }

    public function testDescription()
    {
        /** @var string $locale */
        $locale = $this->deliveryGroup->getDefaultLocale();
        $this->assertInstanceOf(DeliveryGroup::class, $this->deliveryGroup->setDescription('description', $locale));
        $this->assertEquals('description', $this->deliveryGroup->getDescription());
    }

    /**
     * @expectedException \TypeError
     */
    public function testDescriptionTypeError()
    {
        /** @var string $locale */
        $locale = $this->deliveryGroup->getDefaultLocale();
        $this->deliveryGroup->setDescription(123, $locale);
    }
}
