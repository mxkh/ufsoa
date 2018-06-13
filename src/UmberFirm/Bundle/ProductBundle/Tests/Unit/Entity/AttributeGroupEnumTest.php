<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroup;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroupEnum;

/**
 * Class AttributeGroupEnumTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity
 */
class AttributeGroupEnumTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AttributeGroupEnum
     */
    private $attributeGroupEnum;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->attributeGroupEnum = new AttributeGroupEnum();
    }

    public function testName()
    {
        $this->assertInstanceOf(AttributeGroupEnum::class, $this->attributeGroupEnum->setName('string'));
        $this->assertEquals('string', $this->attributeGroupEnum->getName());
    }

    public function testNameNullable()
    {
        $this->attributeGroupEnum->setName(null);
        $this->assertInternalType('string', $this->attributeGroupEnum->getName());
    }

    /**
     * @expectedException \TypeError
     */
    public function testNameWithWrongArgumentType()
    {
        $this->attributeGroupEnum->setName(123);
    }

    public function testAttributeGroup()
    {
        $this->assertInstanceOf(
            AttributeGroupEnum::class,
            $this->attributeGroupEnum->addAttributeGroup(new AttributeGroup())
        );
    }

    /**
     * @expectedException \TypeError
     */
    public function testAttributeGroupWithWrongArgumentType()
    {
        $this->attributeGroupEnum->addAttributeGroup(null);
    }
}
