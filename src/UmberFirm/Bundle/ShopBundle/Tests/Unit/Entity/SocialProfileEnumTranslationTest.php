<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\ShopBundle\Entity\SocialProfileEnumTranslation;

/**
 * Class SocialProfileEnumTranslationTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity
 */
class SocialProfileEnumTranslationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SocialProfileEnumTranslation
     */
    private $socialProfileEnumTranslation;

    /**
     *{@inheritdoc}
     */
    protected function setUp()
    {
        $this->socialProfileEnumTranslation = new SocialProfileEnumTranslation();
    }

    public function testName()
    {
        $this->assertInstanceOf(
            SocialProfileEnumTranslation::class,
            $this->socialProfileEnumTranslation->setName("100")
        );
        $this->assertInternalType('string', $this->socialProfileEnumTranslation->getName());
        $this->assertEquals("100", $this->socialProfileEnumTranslation->getName());
        $this->socialProfileEnumTranslation->setName("100");
    }

    /**
     * @expectedException \TypeError
     */
    public function testWrongValue()
    {
        $this->socialProfileEnumTranslation->setName(123);
    }

    /**
     * @expectedException \TypeError
     */
    public function testNullValue()
    {
        $this->socialProfileEnumTranslation->setName();
    }

    public function testAlias()
    {
        $this->assertInstanceOf(
            SocialProfileEnumTranslation::class,
            $this->socialProfileEnumTranslation->setAlias("100")
        );
        $this->assertInternalType('string', $this->socialProfileEnumTranslation->getAlias());
        $this->assertEquals("100", $this->socialProfileEnumTranslation->getAlias());
        $this->socialProfileEnumTranslation->setAlias("100");
    }

    /**
     * @expectedException \TypeError
     */
    public function testWrongAlias()
    {
        $this->socialProfileEnumTranslation->setAlias(123);
    }

    /**
     * @expectedException \TypeError
     */
    public function testNullAlias()
    {
        $this->socialProfileEnumTranslation->setAlias();
    }
}
