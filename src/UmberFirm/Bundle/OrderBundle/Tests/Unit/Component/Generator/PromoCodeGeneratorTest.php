<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Tests\Unit\Component\Generator;

use Hackzilla\PasswordGenerator\Generator\HybridPasswordGenerator;
use UmberFirm\Bundle\OrderBundle\Component\Generator\PromoCodeGenerator;

/**
 * Class PromoCodeGeneratorTest
 *
 * @package UmberFirm\Bundle\OrderBundle\Tests\Unit\Component\Generator
 */
class PromoCodeGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HybridPasswordGenerator|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $generator;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->generator = $this->createMock(HybridPasswordGenerator::class);
    }

    public function testGenerate()
    {
        $length = 4;
        $this->generator
            ->expects($this->once())
            ->method('setSegmentLength')
            ->with($length);

        $count = 4;
        $this->generator
            ->expects($this->once())
            ->method('setSegmentCount')
            ->with($count);

        $code = '1234-ABCD-5678-EFGH';
        $this->generator
            ->expects($this->once())
            ->method('generatePassword')
            ->willReturn($code);

        $promoCodeGenerator = new PromoCodeGenerator($this->generator);
        $promoCodeGenerator
            ->setSegmentLength($length)
            ->setSegmentCount($count);
        $result = $promoCodeGenerator->generate();

        $this->assertEquals($result, $code);
    }

    public function testSetSegmentLength()
    {
        $promoCodeGenerator = new PromoCodeGenerator($this->generator);

        $length = 5;
        $this->generator
            ->expects($this->atLeastOnce())
            ->method('setSegmentLength')
            ->with($length);

        $promoCodeGenerator->setSegmentLength($length);
    }

    public function testSetSegmentCount()
    {
        $promoCodeGenerator = new PromoCodeGenerator($this->generator);

        $count = 5;
        $this->generator
            ->expects($this->atLeastOnce())
            ->method('setSegmentCount')
            ->with($count);

        $promoCodeGenerator->setSegmentCount($count);
    }
}
