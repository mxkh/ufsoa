<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\ProductBundle\Entity\Feature;
use UmberFirm\Bundle\ProductBundle\Entity\FeatureValue;

/**
 * Class FeatureTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity
 */
class FeatureTest extends \PHPUnit_Framework_TestCase
{
    /** @var Feature */
    private $feature;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->feature = new Feature();

        parent::setUp();
    }

    public function testPosition()
    {
        $this->assertInstanceOf(Feature::class, $this->feature->setPosition(1));
        $this->assertEquals(1, $this->feature->getPosition());
    }

    public function testPositionNullable()
    {
        $this->feature->setPosition(null);
        $this->assertInternalType('int', $this->feature->getPosition());
    }

    /**
     * @expectedException \TypeError
     */
    public function testPositionWithWrongArgumentType()
    {
        $this->feature->setPosition('string');
    }

    public function testFeatureValues()
    {
        $this->assertInstanceOf(Feature::class, $this->feature->addFeatureValue(new FeatureValue()));
        $this->assertInstanceOf(Collection::class, $this->feature->getFeatureValues());
        $this->assertInstanceOf(FeatureValue::class, $this->feature->getFeatureValues()->first());
        $this->assertInstanceOf(
            Feature::class,
            $this->feature->removeFeatureValue($this->feature->getFeatureValues()->first())
        );
        $this->assertEquals(0, $this->feature->getFeatureValues()->count());
    }

    /**
     * @expectedException \TypeError
     */
    public function testFeatureValuesWithWrongArgumentType()
    {
        $this->feature->addFeatureValue(null);
    }

    public function testName()
    {
        $this->assertInstanceOf(Feature::class, $this->feature->setName('string', 'en'));
        $this->assertEquals('string', $this->feature->getName());
    }

    /**
     * @expectedException \TypeError
     */
    public function testNameWithWrongNameArgumentType()
    {
        $this->feature->setName(null, 'en');
    }

    /**
     * @expectedException \TypeError
     */
    public function testNameWithWrongLocaleArgumentType()
    {
        $this->feature->setName('string', null);
    }
}
