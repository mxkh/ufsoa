<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Order\Builder;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerAddress;
use UmberFirm\Bundle\OrderBundle\Entity\Order;
use UmberFirm\Bundle\OrderBundle\Entity\Promocode;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopCurrency;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDelivery;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPayment;

/**
 * Interface OrderBuilderInterface
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Order\Builder
 */
interface OrderBuilderInterface
{
    /**
     * @param ShoppingCart $shoppingCart
     *
     * @return OrderBuilderInterface
     */
    public function createOrder(ShoppingCart $shoppingCart): OrderBuilderInterface;

    /**
     * @param Collection $shoppingCartItems
     *
     * @return OrderBuilderInterface
     */
    public function addOrderItems(Collection $shoppingCartItems): OrderBuilderInterface;

    /**
     * @param Shop $shop
     *
     * @return OrderBuilderInterface
     */
    public function addShop(Shop $shop): OrderBuilderInterface;

    /**
     * @param ShopCurrency $shopCurrency
     *
     * @return OrderBuilderInterface
     */
    public function addCurrency(ShopCurrency $shopCurrency): OrderBuilderInterface;

    /**
     * @param null|Customer $customer
     *
     * @return OrderBuilderInterface
     */
    public function addCustomer(?Customer $customer): OrderBuilderInterface;

    /**
     * @param null|Promocode $promocode
     *
     * @return OrderBuilderInterface
     */
    public function addPromocode(?Promocode $promocode): OrderBuilderInterface;

    /**
     * @param ShopPayment $shopPayment
     *
     * @return OrderBuilderInterface
     */
    public function addPayment(ShopPayment $shopPayment): OrderBuilderInterface;

    /**
     * @param ShopDelivery $delivery
     *
     * @return OrderBuilderInterface
     */
    public function addDelivery(ShopDelivery $delivery): OrderBuilderInterface;

    /**
     * @param CustomerAddress $customerAddress
     *
     * @return OrderBuilderInterface
     */
    public function addShippingAddress(CustomerAddress $customerAddress): OrderBuilderInterface;

    /**
     * @param string $note
     *
     * @return OrderBuilderInterface
     */
    public function addNote(string $note): OrderBuilderInterface;

    /**
     * @return Order
     */
    public function getOrder(): Order;
}
