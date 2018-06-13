<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PaymentBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\PaymentBundle\Entity\Payment;

/**
 * Class PaymentTest
 *
 * @package UmberFirm\Bundle\PaymentBundle\Tests\Unit\Entity
 */
class PaymentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Payment
     */
    private $payment;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->payment = new Payment();
    }

    public function testCode()
    {
        $this->assertInstanceOf(Payment::class, $this->payment->setCode(null));
        $this->assertInternalType('string', $this->payment->getCode());
        $this->assertInstanceOf(Payment::class, $this->payment->setCode('cash'));
        $this->assertEquals('cash', $this->payment->getCode());
    }

    /**
     * @expectedException \TypeError
     */
    public function testCodeTypeError()
    {
        $this->payment->setCode(123);
    }

    public function testType()
    {
        $this->assertInstanceOf(Payment::class, $this->payment->setType(null));
        $this->assertInternalType('integer', $this->payment->getType());
        $this->assertInstanceOf(Payment::class, $this->payment->setType(Payment::ONLINE));
        $this->assertEquals(Payment::ONLINE, $this->payment->getType());
    }

    /**
     * @expectedException \TypeError
     */
    public function testTypeTypeError()
    {
        $this->payment->setType('string');
    }

    public function testName()
    {
        /** @var string $locale */
        $locale = $this->payment->getDefaultLocale();
        $this->assertInstanceOf(Payment::class, $this->payment->setName('name', $locale));
        $this->assertEquals('name', $this->payment->getName());
    }

    /**
     * @expectedException \TypeError
     */
    public function testNameTypeError()
    {
        /** @var string $locale */
        $locale = $this->payment->getDefaultLocale();
        $this->payment->setName(123, $locale);
    }

    public function testDescription()
    {
        /** @var string $locale */
        $locale = $this->payment->getDefaultLocale();
        $this->assertInstanceOf(Payment::class, $this->payment->setDescription('description', $locale));
        $this->assertEquals('description', $this->payment->getDescription());
    }

    /**
     * @expectedException \TypeError
     */
    public function testDescriptionTypeError()
    {
        /** @var string $locale */
        $locale = $this->payment->getDefaultLocale();
        $this->payment->setDescription(123, $locale);
    }
}
