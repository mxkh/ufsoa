<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\ProductBundle\Entity\Attribute;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroup;

/**
 * Class AttributeTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity
 */
class AttributeTest extends \PHPUnit_Framework_TestCase
{
    /** @var Attribute */
    private $attribute;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->attribute = new Attribute();
    }

    public function testAttribute()
    {
        $this->assertInstanceOf(Attribute::class, $this->attribute->setAttributeGroup(new AttributeGroup()));
        $this->assertInstanceOf(AttributeGroup::class, $this->attribute->getAttributeGroup());
    }

    public function testAttributeNullable()
    {
        $this->attribute->setAttributeGroup(null);
        $this->assertEquals(null, $this->attribute->getAttributeGroup());
    }

    /**
     * @expectedException \TypeError
     */
    public function testAttributeWithWrongArgumentType()
    {
        $this->attribute->setAttributeGroup('string');
    }

    public function testColor()
    {
        $this->assertInstanceOf(Attribute::class, $this->attribute->setColor('red'));
        $this->assertEquals('red', $this->attribute->getColor());
    }

    public function testColorNullable()
    {
        $this->attribute->setColor(null);
        $this->assertEquals(null, $this->attribute->getColor());
    }

    /**
     * @expectedException \TypeError
     */
    public function testColorWithWrongArgumentType()
    {
        $this->attribute->setColor(123);
    }

    public function testPosition()
    {
        $this->assertInstanceOf(Attribute::class, $this->attribute->setPosition(2));
        $this->assertEquals(2, $this->attribute->getPosition());
    }

    public function testPositionNullable()
    {
        $this->attribute->setPosition(null);
        $this->assertEquals(null, $this->attribute->getPosition());
    }

    /**
     * @expectedException \TypeError
     */
    public function testPositionWithWrongArgumentType()
    {
        $this->attribute->setPosition('1');
    }

    public function testName()
    {
        $this->assertInstanceOf(Attribute::class, $this->attribute->setName('string', 'en'));
        $this->assertEquals('string', $this->attribute->getName());
    }

    /**
     * @expectedException \TypeError
     */
    public function testNameWithWrongNameArgumentType()
    {
        $this->attribute->setName(null, 'en');
    }

    /**
     * @expectedException \TypeError
     */
    public function testNameWithWrongLocaleArgumentType()
    {
        $this->attribute->setName('string', null);
    }
}
