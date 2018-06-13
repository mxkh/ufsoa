<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\MediaBundle\Entity\MediaEnum;

/**
 * Class MediaEnumTest
 *
 * @package UmberFirm\Bundle\MediaBundle\Tests\Unit\Entity
 */
class MediaEnumTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MediaEnum
     */
    private $mediaEnum;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->mediaEnum = new MediaEnum();
    }

    public function testName()
    {
        $this->assertEquals(null, $this->mediaEnum->getName());
        $this->assertInstanceOf(MediaEnum::class, $this->mediaEnum->setName('image'));
        $this->assertEquals('image', $this->mediaEnum->getName());
    }

    /**
     * @expectedException \TypeError
     */
    public function testNameNotNullable()
    {
        $this->mediaEnum->setName(123);
    }

    public function testMedias()
    {
        $this->assertInstanceOf(Collection::class, $this->mediaEnum->getMedias());
    }

    public function testMimeTypes()
    {
        $this->assertInstanceOf(Collection::class, $this->mediaEnum->getMimeTypes());
    }
}
