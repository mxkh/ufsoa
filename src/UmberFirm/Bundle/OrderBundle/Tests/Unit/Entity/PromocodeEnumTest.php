<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\OrderBundle\Entity\PromocodeEnum;

/**
 * Class PromocodeEnumTest
 *
 * @package UmberFirm\Bundle\OrderBundle\Tests\Unit\Entity
 */
class PromocodeEnumTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PromocodeEnum
     */
    private $promocodeEnum;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->promocodeEnum = new PromocodeEnum();
    }

    public function testCode()
    {
        $this->assertInstanceOf(PromocodeEnum::class, $this->promocodeEnum->setCode(null));
        $this->assertInternalType('string', $this->promocodeEnum->getCode());
        $this->assertInstanceOf(PromocodeEnum::class, $this->promocodeEnum->setCode('percent'));
        $this->assertEquals('percent', $this->promocodeEnum->getCode());
    }

    /**
     * @expectedException \TypeError
     */
    public function testCodeTypeError()
    {
        $this->promocodeEnum->setCode(123);
    }

    public function testCalculate()
    {
        $this->assertInstanceOf(PromocodeEnum::class, $this->promocodeEnum->setCalculate(null));
        $this->assertInternalType('string', $this->promocodeEnum->getCalculate());
        $this->assertInstanceOf(PromocodeEnum::class, $this->promocodeEnum->setCalculate('%s * (%s / 100)'));
        $this->assertEquals('%s * (%s / 100)', $this->promocodeEnum->getCalculate());
    }

    /**
     * @expectedException \TypeError
     */
    public function testCalculateTypeError()
    {
        $this->promocodeEnum->setCalculate(123);
    }

    public function testName()
    {
        $this->assertInstanceOf(PromocodeEnum::class, $this->promocodeEnum->setName('name', 'en'));
        $this->assertEquals('name', $this->promocodeEnum->getName());
    }

    /**
     * @expectedException \TypeError
     */
    public function testNameWithWrongNameArgumentType()
    {
        $this->promocodeEnum->setName(123, 'en');
    }

    /**
     * @expectedException \TypeError
     */
    public function testNameWithWrongLocaleArgumentType()
    {
        $this->promocodeEnum->setName('name', 123);
    }

    public function testNameReturnsNullable()
    {
        $this->assertEquals(null, $this->promocodeEnum->getName());
    }
}
