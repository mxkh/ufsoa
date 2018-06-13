<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Entity;

use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;

/**
 * Class FastOrder
 *
 * @package UmberFirm\Bundle\OrderBundle\Entity
 */
class FastOrder
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var ProductVariant
     */
    private $productVariant;

    /**
     * @var Promocode
     */
    private $promocode;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return (string) $this->email;
    }

    /**
     * @param null|string $email
     *
     * @return FastOrder
     */
    public function setEmail(?string $email): FastOrder
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return (string) $this->phone;
    }

    /**
     * @param null|string $phone
     *
     * @return FastOrder
     */
    public function setPhone(?string $phone): FastOrder
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return null|ProductVariant
     */
    public function getProductVariant(): ?ProductVariant
    {
        return $this->productVariant;
    }

    /**
     * @param null|ProductVariant $productVariant
     *
     * @return FastOrder
     */
    public function setProductVariant(?ProductVariant $productVariant): FastOrder
    {
        $this->productVariant = $productVariant;

        return $this;
    }

    /**
     * @param null|Promocode $promocode
     *
     * @return FastOrder
     */
    public function setPromocode(?Promocode $promocode): FastOrder
    {
        $this->promocode = $promocode;

        return $this;
    }

    /**
     * @return null|Promocode
     */
    public function getPromocode(): ?Promocode
    {
        return $this->promocode;
    }

    /**
     * @return string
     */
    public function getPromocodeId(): string
    {
        return null === $this->promocode ? '' : $this->promocode->getId()->toString();
    }
}
