<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\Promocode;
use UmberFirm\Bundle\OrderBundle\Entity\PromocodeEnum;

/**
 * Class PromocodeTest
 *
 * @package UmberFirm\Bundle\OrderBundle\Tests\Unit\Entity
 */
class PromocodeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Promocode
     */
    private $promocode;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->promocode = new Promocode();
    }

    public function testCode()
    {
        $this->assertInstanceOf(Promocode::class, $this->promocode->setCode(null));
        $this->assertInternalType('string', $this->promocode->getCode());
        $this->assertInstanceOf(Promocode::class, $this->promocode->setCode('SALE50'));
        $this->assertEquals('SALE50', $this->promocode->getCode());
    }

    /**
     * @expectedException \TypeError
     */
    public function testCodeTypeError()
    {
        $this->promocode->setCode(123);
    }

    public function testValue()
    {
        $this->assertInstanceOf(Promocode::class, $this->promocode->setValue(null));
        $this->assertInternalType('integer', $this->promocode->getValue());
        $this->assertInstanceOf(Promocode::class, $this->promocode->setValue(50));
        $this->assertEquals(50, $this->promocode->getValue());
    }

    /**
     * @expectedException \TypeError
     */
    public function testValueTypeError()
    {
        $this->promocode->setValue('string');
    }

    public function testCustomer()
    {
        $this->assertInstanceOf(Promocode::class, $this->promocode->setCustomer(null));
        $this->assertInternalType('null', $this->promocode->getCustomer());
        $this->assertInstanceOf(Promocode::class, $this->promocode->setCustomer(new Customer()));
        $this->assertInstanceOf(Customer::class, $this->promocode->getCustomer());
    }

    /**
     * @expectedException \TypeError
     */
    public function testCustomerTypeError()
    {
        $this->promocode->setCustomer(new \stdClass());
    }

    public function testStart()
    {
        $this->assertInstanceOf(Promocode::class, $this->promocode->setStart(null));
        $this->assertInternalType('null', $this->promocode->getStart());
        $this->assertInstanceOf(Promocode::class, $this->promocode->setStart(new \DateTime('now')));
        $this->assertInstanceOf(\DateTime::class, $this->promocode->getStart());
    }

    /**
     * @expectedException \TypeError
     */
    public function testStartTypeError()
    {
        $this->promocode->setStart(new \stdClass());
    }

    public function testFinish()
    {
        $this->assertInstanceOf(Promocode::class, $this->promocode->setFinish(null));
        $this->assertInternalType('null', $this->promocode->getFinish());
        $this->assertInstanceOf(Promocode::class, $this->promocode->setFinish(new \DateTime('now')));
        $this->assertInstanceOf(\DateTime::class, $this->promocode->getFinish());
    }

    /**
     * @expectedException \TypeError
     */
    public function testFinishTypeError()
    {
        $this->promocode->setFinish(new \stdClass());
    }

    public function testReusable()
    {
        $this->assertInstanceOf(Promocode::class, $this->promocode->setIsReusable(null));
        $this->assertInternalType('bool', $this->promocode->getIsReusable());
        $this->assertInstanceOf(Promocode::class, $this->promocode->setIsReusable(true));
        $this->assertEquals(true, $this->promocode->getIsReusable());
    }

    /**
     * @expectedException \TypeError
     */
    public function testReusableTypeError()
    {
        $this->promocode->setIsReusable('string');
    }

    public function testLimiting()
    {
        $this->assertInstanceOf(Promocode::class, $this->promocode->setLimiting(null));
        $this->assertInternalType('integer', $this->promocode->getLimiting());
        $this->assertInstanceOf(Promocode::class, $this->promocode->setLimiting(100));
        $this->assertEquals(100, $this->promocode->getLimiting());
    }

    /**
     * @expectedException \TypeError
     */
    public function testLimitingTypeError()
    {
        $this->promocode->setLimiting('string');
    }

    public function testUsed()
    {
        $this->assertEquals(0, $this->promocode->getUsed());
        $this->assertInstanceOf(Promocode::class, $this->promocode->used());
        $this->assertEquals(1, $this->promocode->getUsed());
        $this->assertInstanceOf(Promocode::class, $this->promocode->used());
        $this->assertEquals(2, $this->promocode->getUsed());
    }

    public function testPromocodeEnum()
    {
        $this->assertInstanceOf(Promocode::class, $this->promocode->setPromocodeEnum(null));
        $this->assertInternalType('null', $this->promocode->getPromocodeEnum());
        $this->assertInstanceOf(Promocode::class, $this->promocode->setPromocodeEnum(new PromocodeEnum()));
        $this->assertInstanceOf(PromocodeEnum::class, $this->promocode->getPromocodeEnum());
    }

    /**
     * @expectedException \TypeError
     */
    public function testPromocodeEnumTypeError()
    {
        $this->promocode->setPromocodeEnum(new \stdClass());
    }
}
