<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\ShopBundle\Entity\SettingsAttribute;

/**
 * Class SettingsAttributeTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity
 */
class SettingsAttributeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SettingsAttribute
     */
    private $settingsAttribute;

    /**
     *{@inheritdoc}
     */
    protected function setUp()
    {
        $this->settingsAttribute = new SettingsAttribute();
    }

    public function testName()
    {
        $this->assertInstanceOf(SettingsAttribute::class, $this->settingsAttribute->setName("test"));
        $this->assertInternalType('string', $this->settingsAttribute->getName());
        $this->assertEquals("test", $this->settingsAttribute->getName());
        $this->settingsAttribute->setName("test");
    }

    /**
     * @expectedException \TypeError
     */
    public function testWrongTypeName()
    {
        $this->settingsAttribute->setName(12.12312313);
    }

    public function testType()
    {
        $this->assertInstanceOf(SettingsAttribute::class, $this->settingsAttribute->setType("boolean"));
        $this->assertInternalType('string', $this->settingsAttribute->getType());
        $this->assertEquals("boolean", $this->settingsAttribute->getType());
        $this->settingsAttribute->setName("boolean");
    }

    /**
     * @expectedException \TypeError
     */
    public function testWrongTypeType()
    {
        $this->settingsAttribute->setType(12.12312313);
    }
}
