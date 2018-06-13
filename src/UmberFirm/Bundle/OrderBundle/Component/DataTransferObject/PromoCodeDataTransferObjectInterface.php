<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Component\DataTransferObject;

/**
 * Interface PromoCodeDataTransferObjectInterface
 *
 * @package UmberFirm\Bundle\OrderBundle\Component\DataTransferObject
 */
interface PromoCodeDataTransferObjectInterface
{
    /**
     * @return string
     */
    public function getPromoCode(): string;
}
