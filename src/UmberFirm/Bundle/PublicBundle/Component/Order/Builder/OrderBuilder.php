<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Order\Builder;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerAddress;
use UmberFirm\Bundle\OrderBundle\Component\Factory\OrderFactoryInterface;
use UmberFirm\Bundle\OrderBundle\Entity\Order;
use UmberFirm\Bundle\OrderBundle\Entity\Promocode;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart;
use UmberFirm\Bundle\PaymentBundle\Component\Manager\PaymentManagerInterface;
use UmberFirm\Bundle\PublicBundle\Component\Order\Manager\PromocodeManagerInterface;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopCurrency;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDelivery;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPayment;

/**
 * Class OrderBuilder
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Order\Builder
 */
class OrderBuilder implements OrderBuilderInterface
{
    /**
     * @var OrderFactoryInterface
     */
    private $orderFactory;

    /**
     * @var PromocodeManagerInterface
     */
    private $promocodeManager;

    /**
     * @var PaymentManagerInterface
     */
    private $paymentManager;

    /**
     * @var Order
     */
    private $order;

    /**
     * OrderBuilder constructor.
     *
     * @param OrderFactoryInterface $orderFactory
     * @param PromocodeManagerInterface $promocodeManager
     * @param PaymentManagerInterface $paymentManager
     */
    public function __construct(
        OrderFactoryInterface $orderFactory,
        PromocodeManagerInterface $promocodeManager,
        PaymentManagerInterface $paymentManager
    ) {
        $this->orderFactory = $orderFactory;
        $this->promocodeManager = $promocodeManager;
        $this->paymentManager = $paymentManager;
    }

    /**
     * {@inheritdoc}
     */
    public function createOrder(ShoppingCart $shoppingCart): OrderBuilderInterface
    {
        $this->order = $this->orderFactory->createFromCart($shoppingCart);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addOrderItems(Collection $shoppingCartItems): OrderBuilderInterface
    {
        foreach ($shoppingCartItems as $shoppingCartItem) {
            $orderItem = $this->orderFactory->createOrderItem($shoppingCartItem);
            $this->order->addOrderItem($orderItem);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addShop(Shop $shop): OrderBuilderInterface
    {
        $this->order->setShop($shop);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addCurrency(ShopCurrency $shopCurrency): OrderBuilderInterface
    {
        $this->order->setShopCurrency($shopCurrency);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addCustomer(?Customer $customer): OrderBuilderInterface
    {
        $this->order->setCustomer($customer);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addPromocode(?Promocode $promocode): OrderBuilderInterface
    {
        if (null === $promocode) {
            return $this;
        }

        $this->order->setPromocode($promocode);
        $this->promocodeManager->incrementUsed($promocode);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addPayment(ShopPayment $shopPayment): OrderBuilderInterface
    {
        $paymentUrl = $this->paymentManager->generatePaymentUrl($shopPayment, $this->order);
        $this->order->setPaymentUrl($paymentUrl);
        $this->order->setShopPayment($shopPayment);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addDelivery(ShopDelivery $shopDelivery): OrderBuilderInterface
    {
        $this->order->setShopDelivery($shopDelivery);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addShippingAddress(CustomerAddress $customerAddress): OrderBuilderInterface
    {
        $this->order->setCustomerAddress($customerAddress);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addNote(string $note): OrderBuilderInterface
    {
        $this->order->setNote($note);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder(): Order
    {
        return $this->order;
    }
}
