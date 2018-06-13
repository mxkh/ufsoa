<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Order\DataTransferObject;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\FastOrder;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class FastOrderDTO
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Order\DataTransferObject
 */
class FastOrderDTO implements FastOrderDTOInterface
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var string UUid of object
     */
    private $productVariant;

    /**
     * @var string UUid of object
     */
    private $customer;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string UUid of object
     */
    private $shop;

    /**
     * @var string UUid of object
     */
    private $promocode;

    /**
     * FastOrderDTO constructor.
     *
     * @param FastOrder $fastOrder
     * @param Shop $shop
     * @param Customer $customer
     */
    public function __construct(FastOrder $fastOrder, Shop $shop, Customer $customer)
    {
        $this->productVariant = $fastOrder->getProductVariant()->getId()->toString();
        $this->customer = $customer->getId()->toString();
        $this->shop = $shop->getId()->toString();
        $this->phone = $fastOrder->getPhone();
        $this->promocode = $fastOrder->getPromocodeId();
        $this->email = $fastOrder->getEmail();
    }

    /**
     * {@inheritdoc}
     */
    public function getProductVariant(): string
    {
        return (string) $this->productVariant;
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomer(): string
    {
        return (string) $this->customer;
    }

    /**
     * {@inheritdoc}
     */
    public function getPhone(): string
    {
        return (string) $this->phone;
    }

    /**
     * {@inheritdoc}
     */
    public function getShop(): string
    {
        return (string) $this->shop;
    }

    /**
     * {@inheritdoc}
     */
    public function getPromocode(): string
    {
        return (string) $this->promocode;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail(): string
    {
        return (string) $this->email;
    }
}
