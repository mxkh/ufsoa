<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Component\DataTransferObject\Factory;

use UmberFirm\Bundle\OrderBundle\Component\DataTransferObject\PromoCodeDataTransferObjectInterface;

/**
 * Interface PromoCodeDataTransferObjectFactoryInterface
 *
 * @package UmberFirm\Bundle\OrderBundle\Component\DataTransferObject\Factory
 */
interface PromoCodeDataTransferObjectFactoryInterface
{
    /**
     * @param string $promoCode
     *
     * @return PromoCodeDataTransferObjectInterface
     */
    public function create(string $promoCode): PromoCodeDataTransferObjectInterface;
}
