<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Customer\Generator;

use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;
use UmberFirm\Bundle\PublicBundle\Component\Customer\Generator\ConfirmationCodeGenerator;

/**
 * Class ConfirmationCodeGeneratorTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Customer\Generator
 */
class ConfirmationCodeGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ComputerPasswordGenerator|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $generator;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->generator = $this->createMock(ComputerPasswordGenerator::class);
    }

    public function testGenerate()
    {
        $length = 4;
        $this->generator
            ->expects($this->once())
            ->method('setLength')
            ->with($length);

        $code = '1234';
        $this->generator
            ->expects($this->once())
            ->method('generatePassword')
            ->willReturn($code);

        $confirmationCodeGenerator = new ConfirmationCodeGenerator($this->generator, $length);
        $result = $confirmationCodeGenerator->generate();

        $this->assertEquals($result, $code);
    }

    public function testSetLength()
    {
        $length = 4;
        $confirmationCodeGenerator = new ConfirmationCodeGenerator($this->generator, $length);

        $newLength = 5;
        $this->generator
            ->expects($this->atLeastOnce())
            ->method('setLength')
            ->with($newLength);
        $confirmationCodeGenerator->setLength($newLength);
    }
}
