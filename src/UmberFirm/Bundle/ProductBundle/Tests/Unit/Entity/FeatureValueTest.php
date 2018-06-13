<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\ProductBundle\Entity\Feature;
use UmberFirm\Bundle\ProductBundle\Entity\FeatureValue;

/**
 * Class FeatureValueTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity
 */
class FeatureValueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FeatureValue
     */
    private $featureValue;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->featureValue = new FeatureValue();

        parent::setUp();
    }

    public function testFeature()
    {
        $this->assertInstanceOf(FeatureValue::class, $this->featureValue->setFeature(new Feature()));
        $this->assertInstanceOf(Feature::class, $this->featureValue->getFeature());
    }

    public function testFeatureNullable()
    {
        $this->featureValue->setFeature(null);
        $this->assertNull($this->featureValue->getFeature());
    }

    /**
     * @expectedException \TypeError
     */
    public function testFeatureWithWrongArgumentType()
    {
        $this->featureValue->setFeature(123);
    }

    public function testValue()
    {
        $this->assertInstanceOf(FeatureValue::class, $this->featureValue->setValue('string', 'en'));
        $this->assertEquals('string', $this->featureValue->getValue());
    }

    /**
     * @expectedException \TypeError
     */
    public function testValueWithWrongValueArgumentType()
    {
        $this->featureValue->setValue(null, 'en');
    }

    /**
     * @expectedException \TypeError
     */
    public function testValueWithWrongLocaleArgumentType()
    {
        $this->featureValue->setValue('string', null);
    }
}
