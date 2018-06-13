<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\ProductBundle\Entity\Attribute;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroup;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroupEnum;

/**
 * Class AttributeGroupTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity
 */
class AttributeGroupTest extends \PHPUnit_Framework_TestCase
{
    /** @var AttributeGroup */
    private $attributeGroup;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->attributeGroup = new AttributeGroup();
    }

    public function testIsColorGroup()
    {
        $this->assertInstanceOf(AttributeGroup::class, $this->attributeGroup->setIsColorGroup(true));
        $this->assertTrue($this->attributeGroup->getIsColorGroup());
    }

    /**
     * @expectedException \TypeError
     */
    public function testIsColorGroupWithWrongArgumentType()
    {
        $this->attributeGroup->setIsColorGroup(null);
    }

    public function testAttributeGroupEnum()
    {
        $this->assertInstanceOf(
            AttributeGroup::class,
            $this->attributeGroup->setAttributeGroupEnum(new AttributeGroupEnum())
        );
        $this->assertInstanceOf(AttributeGroupEnum::class, $this->attributeGroup->getAttributeGroupEnum());
    }

    public function testAttributeGroupEnumNullable()
    {
        $this->attributeGroup->setAttributeGroupEnum(null);
        $this->assertNull($this->attributeGroup->getAttributeGroupEnum());
    }

    /**
     * @expectedException \TypeError
     */
    public function testAttributeGroupWithWrongArgumentType()
    {
        $this->attributeGroup->setAttributeGroupEnum(false);
    }

    public function testPosition()
    {
        $this->assertInstanceOf(AttributeGroup::class, $this->attributeGroup->setPosition(1));
        $this->assertEquals(1, $this->attributeGroup->getPosition());
    }

    public function testPositionNullable()
    {
        $this->attributeGroup->setPosition(null);
        $this->assertInternalType('int', $this->attributeGroup->getPosition());
    }

    /**
     * @expectedException \TypeError
     */
    public function testPositionWithWrongArgumentType()
    {
        $this->attributeGroup->setPosition('1');
    }

    public function testAttributes()
    {
        $this->assertInstanceOf(AttributeGroup::class, $this->attributeGroup->addAttributes(new Attribute()));
        $this->assertInstanceOf(Collection::class, $this->attributeGroup->getAttributes());
        $this->assertInstanceOf(Attribute::class, $this->attributeGroup->getAttributes()->first());
    }

    /**
     * @expectedException \TypeError
     */
    public function testAttributesWithWrongArgumentType()
    {
        $this->attributeGroup->addAttributes(null);
    }

    public function testName()
    {
        $this->assertInstanceOf(AttributeGroup::class, $this->attributeGroup->setName('string', 'en'));
        $this->assertEquals('string', $this->attributeGroup->getName());
    }

    /**
     * @expectedException \TypeError
     */
    public function testNameWithWrongNameArgumentType()
    {
        $this->attributeGroup->setName(null, 'en');
    }

    /**
     * @expectedException \TypeError
     */
    public function testNameWithWrongLocaleArgumentType()
    {
        $this->attributeGroup->setName('string', null);
    }

    public function testPublicName()
    {
        $this->assertInstanceOf(AttributeGroup::class, $this->attributeGroup->setPublicName('string', 'en'));
        $this->assertEquals('string', $this->attributeGroup->getPublicName());
    }

    /**
     * @expectedException \TypeError
     */
    public function testPublicNameWithWrongPublicNameArgumentType()
    {
        $this->attributeGroup->setPublicName(null, 'en');
    }

    /**
     * @expectedException \TypeError
     */
    public function testPublicNameWithWrongLocaleArgumentType()
    {
        $this->attributeGroup->setPublicName('string', null);
    }
}
