<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Component\DataTransferObject;

/**
 * Class PromoCodeDataTransferObject
 *
 * @package UmberFirm\Bundle\OrderBundle\Component\DataTransferObject
 */
class PromoCodeDataTransferObject implements PromoCodeDataTransferObjectInterface
{
    /**
     * @var string
     */
    private $promoCode;

    /**
     * PromoCodeDataTransferObject constructor.
     *
     * @param string $promoCode
     */
    public function __construct(string $promoCode)
    {
        $this->promoCode = $promoCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getPromoCode(): string
    {
        return $this->promoCode;
    }
}
