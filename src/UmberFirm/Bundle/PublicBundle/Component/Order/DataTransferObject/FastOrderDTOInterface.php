<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Order\DataTransferObject;

/**
 * Interface FastOrderDTOInterface
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Order\DataTransferObject
 */
interface FastOrderDTOInterface
{
    /**
     * @return string
     */
    public function getProductVariant(): string;

    /**
     * @return string
     */
    public function getCustomer(): string;

    /**
     * @return string
     */
    public function getPhone(): string;

    /**
     * @return string
     */
    public function getShop(): string;

    /**
     * @return string
     */
    public function getPromocode(): string;

    /**
     * @return string
     */
    public function getEmail(): string;
}
