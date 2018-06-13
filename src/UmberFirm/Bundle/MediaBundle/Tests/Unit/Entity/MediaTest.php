<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\MediaBundle\Entity\Media;
use UmberFirm\Bundle\MediaBundle\Entity\MediaEnum;

/**
 * Class MediaTest
 *
 * @package UmberFirm\Bundle\MediaBundle\Tests\Unit\Entity
 */
class MediaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Media
     */
    private $media;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->media = new Media();
    }

    public function testMediaEnumDefault()
    {
        $this->assertInstanceOf(Media::class, $this->media->setMediaEnum(null));
        $this->assertEquals(null, $this->media->getMediaEnum());
    }

    /**
     * @expectedException \TypeError
     */
    public function testMediaEnumWrongArgument()
    {
        $this->assertInstanceOf(Media::class, $this->media->setMediaEnum(123124));
    }

    public function testMediaEnumObject()
    {
        $this->assertInstanceOf(Media::class, $this->media->setMediaEnum(new MediaEnum()));
        $this->assertInstanceOf(MediaEnum::class, $this->media->getMediaEnum());
    }

    public function testFilename()
    {
        $this->assertInstanceOf(Media::class, $this->media->setFilename('1.jpg'));
        $this->assertEquals('1.jpg', $this->media->getFilename());
    }

    public function testFilenameNullable()
    {
        $this->assertInstanceOf(Media::class, $this->media->setFilename(null));
        $this->assertEquals(null, $this->media->getFilename());
    }

    /**
     * @expectedException \TypeError
     */
    public function testFilenameTypeError()
    {
        $this->media->setFilename(123);
    }

    public function testMimeTypeNullable()
    {
        $this->assertInstanceOf(Media::class, $this->media->setMimeType(null));
        $this->assertEquals(null, $this->media->getMimeType());
    }

    public function testMimeType()
    {
        $this->assertInstanceOf(Media::class, $this->media->setMimeType('image/jpeg'));
        $this->assertEquals('image/jpeg', $this->media->getMimeType());
    }

    /**
     * @expectedException \TypeError
     */
    public function testMimeTypeTypeError()
    {
        $this->media->setMimeType(213);
    }

    public function testExtension()
    {
        $this->assertInstanceOf(Media::class, $this->media->setExtension('jpg'));
        $this->assertEquals('jpg', $this->media->getExtension());
    }
}
