<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Event\Order;

use Symfony\Component\EventDispatcher\Event;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\FastOrder;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class FastOrderEvent
 *
 * @package UmberFirm\Bundle\PublicBundle\Event\Order
 */
class FastOrderEvent extends Event implements FastOrderEventInterface
{
    /**
     * @var FastOrder
     */
    private $fastOrder;

    /**
     * @var Shop
     */
    private $shop;

    /**
     * @var null|Customer
     */
    private $customer;

    /**
     * FastOrderEvent constructor.
     *
     * @param FastOrder $fastOrder
     * @param Shop $shop
     * @param null|Customer $customer
     */
    public function __construct(FastOrder $fastOrder, Shop $shop, ?Customer $customer)
    {
        $this->fastOrder = $fastOrder;
        $this->shop = $shop;
        $this->customer = $customer;
    }

    /**
     * {@inheritdoc}
     */
    public function getFastOrder(): FastOrder
    {
        return $this->fastOrder;
    }

    /**
     * {@inheritdoc}
     */
    public function getShop(): Shop
    {
        return $this->shop;
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }
}
