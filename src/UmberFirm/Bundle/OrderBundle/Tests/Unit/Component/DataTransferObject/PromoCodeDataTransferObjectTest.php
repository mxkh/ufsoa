<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Tests\Unit\Component\DataTransferObject;

use UmberFirm\Bundle\OrderBundle\Component\DataTransferObject\PromoCodeDataTransferObject;

class PromoCodeDataTransferObjectTest  extends \PHPUnit_Framework_TestCase
{
    public function testGetPromoCode()
    {
        $code = 'A1B2-E3R4-SALE-0050';
        $promoCode = new PromoCodeDataTransferObject($code);

        $this->assertEquals($code, $promoCode->getPromoCode());
        $this->assertInternalType('string', $promoCode->getPromoCode());
    }
}
