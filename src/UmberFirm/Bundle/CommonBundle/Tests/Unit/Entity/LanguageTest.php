<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\CommonBundle\Entity\Language;

/**
 * Class LanguageTest
 *
 * @package UmberFirm\Bundle\CommonBundle\Tests\Unit\Entity
 */
class LanguageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Language
     */
    private $language;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->language = new Language();
    }

    public function testCode()
    {
        $this->assertInstanceOf(Language::class, $this->language->setCode('US'));
        $this->assertEquals('US', $this->language->getCode());
    }

    public function testCodeNULLArgumentType()
    {
        $this->assertInstanceOf(Language::class, $this->language->setCode(null));
        $this->assertEquals(null, $this->language->getCode());
    }

    /**
     * @expectedException \TypeError
     */
    public function testCodeWrongArgumentType()
    {
        $this->language->setCode(123);
    }

    public function testName()
    {
        $this->assertInstanceOf(Language::class, $this->language->setName('USA'));
        $this->assertEquals('USA', $this->language->getName());
    }

    public function testNameNULLArgumentType()
    {
        $this->assertInstanceOf(Language::class, $this->language->setName(null));
        $this->assertEquals(null, $this->language->getName());
    }

    /**
     * @expectedException \TypeError
     */
    public function testNameWrongArgumentType()
    {
        $this->language->setName(123);
    }
}
