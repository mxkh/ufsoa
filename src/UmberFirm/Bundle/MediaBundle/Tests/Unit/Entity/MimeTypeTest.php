<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\MediaBundle\Entity\MediaEnum;
use UmberFirm\Bundle\MediaBundle\Entity\MimeType;

/**
 * Class MimeTypeTest
 *
 * @package UmberFirm\Bundle\MediaBundle\Tests\Unit\Entity
 */
class MimeTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MimeType
     */
    private $mimeType;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->mimeType = new MimeType();
    }

    public function testMediaEnumNullable()
    {
        $this->assertInstanceOf(MimeType::class, $this->mimeType->setMediaEnum(null));
        $this->assertEquals(null, $this->mimeType->getMediaEnum());
    }

    public function testMediaEnum()
    {
        $this->assertEquals(null, $this->mimeType->getMediaEnum());
        $mediaEnum = new MediaEnum();
        $this->assertInstanceOf(MimeType::class, $this->mimeType->setMediaEnum($mediaEnum));
        $this->assertEquals($mediaEnum, $this->mimeType->getMediaEnum());
    }

    /**
     * @expectedException \TypeError
     */
    public function testMediaEnumTypeError()
    {
        $this->mimeType->setMediaEnum(new \stdClass());
    }

    public function testName()
    {
        $this->assertEquals(null, $this->mimeType->getName());
        $this->assertInstanceOf(MimeType::class, $this->mimeType->setName(null));
        $this->assertEquals(null, $this->mimeType->getName());
        $this->assertInstanceOf(MimeType::class, $this->mimeType->setName('string'));
        $this->assertEquals('string', $this->mimeType->getName());
        $this->assertInternalType('string', $this->mimeType->getName());
    }

    /**
     * @expectedException \TypeError
     */
    public function testNameError()
    {
        $this->mimeType->setName(123);
    }

    public function testTemplate()
    {
        $this->assertEquals(null, $this->mimeType->getTemplate());
        $this->assertInstanceOf(MimeType::class, $this->mimeType->setTemplate(null));
        $this->assertEquals(null, $this->mimeType->getTemplate());
        $this->assertInstanceOf(MimeType::class, $this->mimeType->setTemplate('string'));
        $this->assertEquals('string', $this->mimeType->getTemplate());
        $this->assertInternalType('string', $this->mimeType->getTemplate());
    }

    /**
     * @expectedException \TypeError
     */
    public function testTemplateError()
    {
        $this->mimeType->setTemplate(123);
    }
}
