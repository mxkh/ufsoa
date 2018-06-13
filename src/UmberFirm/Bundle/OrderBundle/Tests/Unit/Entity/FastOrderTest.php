<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\OrderBundle\Entity\FastOrder;
use UmberFirm\Bundle\OrderBundle\Entity\Promocode;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;

/**
 * Class FastOrderTest
 *
 * @package UmberFirm\Bundle\OrderBundle\Tests\Unit\Entity
 */
class FastOrderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FastOrder
     */
    private $fastOrder;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->fastOrder = new FastOrder();
    }

    public function testPhone()
    {
        $this->assertInternalType('string', $this->fastOrder->getPhone());
        $this->assertInstanceOf(FastOrder::class, $this->fastOrder->setPhone('380501231212'));
        $this->assertEquals('380501231212', $this->fastOrder->getPhone());
    }

    /**
     * @expectedException \TypeError
     */
    public function testPhoneTypeError()
    {
        $this->fastOrder->setPhone(3423424);
    }

    public function testProductVariant()
    {
        $this->assertNull($this->fastOrder->getProductVariant());
        $this->assertInstanceOf(FastOrder::class, $this->fastOrder->setProductVariant(null));
        $this->assertInstanceOf(FastOrder::class, $this->fastOrder->setProductVariant(new ProductVariant()));
        $this->assertInstanceOf(ProductVariant::class, $this->fastOrder->getProductVariant());
    }

    /**
     * @expectedException \TypeError
     */
    public function testProductVariantTypeError()
    {
        $this->fastOrder->setProductVariant(123);
    }

    public function testPromocode()
    {
        $this->assertNull($this->fastOrder->getPromocode());
        $this->assertInstanceOf(FastOrder::class, $this->fastOrder->setPromocode(null));
        $this->assertInstanceOf(FastOrder::class, $this->fastOrder->setPromocode(new Promocode()));
        $this->assertInstanceOf(Promocode::class, $this->fastOrder->getPromocode());
    }

    /**
     * @expectedException \TypeError
     */
    public function testPromocodeTypeError()
    {
        $this->fastOrder->setPromocode(123);
    }
}
