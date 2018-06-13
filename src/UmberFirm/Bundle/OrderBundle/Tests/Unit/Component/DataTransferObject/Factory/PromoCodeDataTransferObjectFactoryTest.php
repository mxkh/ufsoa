<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Tests\Unit\Component\DataTransferObject\Factory;

use UmberFirm\Bundle\OrderBundle\Component\DataTransferObject\Factory\PromoCodeDataTransferObjectFactory;
use UmberFirm\Bundle\OrderBundle\Component\DataTransferObject\PromoCodeDataTransferObjectInterface;

class PromoCodeDataTransferObjectFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $factory = new PromoCodeDataTransferObjectFactory();
        $promoCode = $factory->create('A1B2-E3R4-SALE-0050');

        $this->assertInstanceOf(PromoCodeDataTransferObjectInterface::class, $promoCode);
    }
}
