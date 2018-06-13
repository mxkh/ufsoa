<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Component\DataTransferObject\Factory;

use UmberFirm\Bundle\OrderBundle\Component\DataTransferObject\PromoCodeDataTransferObject;
use UmberFirm\Bundle\OrderBundle\Component\DataTransferObject\PromoCodeDataTransferObjectInterface;

/**
 * Class PromoCodeDataTransferObjectFactory
 *
 * @package UmberFirm\Bundle\OrderBundle\Component\DataTransferObject\Factory
 */
class PromoCodeDataTransferObjectFactory implements PromoCodeDataTransferObjectFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(string $promoCode): PromoCodeDataTransferObjectInterface
    {
        return new PromoCodeDataTransferObject($promoCode);
    }
}
