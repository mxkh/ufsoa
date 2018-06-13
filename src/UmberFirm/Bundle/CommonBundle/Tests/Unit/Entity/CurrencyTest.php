<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\CommonBundle\Entity\Currency;

/**
 * Class CurrencyTest
 *
 * @package UmberFirm\Bundle\CommonBundle\Tests\Unit\Entity
 */
class CurrencyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Currency
     */
    private $currency;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->currency = new Currency();
    }

    public function testCode()
    {
        $this->assertInstanceOf(Currency::class, $this->currency->setCode('USD'));
        $this->assertEquals('USD', $this->currency->getCode());
    }

    public function testCodeNULLArgumentType()
    {
        $this->assertInstanceOf(Currency::class, $this->currency->setCode(null));
        $this->assertEquals(null, $this->currency->getCode());
    }

    /**
     * @expectedException \TypeError
     */
    public function testCodeWrongArgumentType()
    {
        $this->currency->setCode(123);
    }

    public function testName()
    {
        $this->assertInstanceOf(Currency::class, $this->currency->setName('U.S. Dollar'));
        $this->assertEquals('U.S. Dollar', $this->currency->getName());
    }

    public function testNameNULLArgumentType()
    {
        $this->assertInstanceOf(Currency::class, $this->currency->setName(null));
        $this->assertEquals(null, $this->currency->getName());
    }

    /**
     * @expectedException \TypeError
     */
    public function testNameWrongArgumentType()
    {
        $this->currency->setName(123);
    }
}
