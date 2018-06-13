<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PaymentBundle\Component\Manager;

use UmberFirm\Bundle\OrderBundle\Entity\Order;
use UmberFirm\Bundle\PaymentBundle\Component\Adapter\PaymentInterface;
use UmberFirm\Bundle\PaymentBundle\Component\Factory\PaymentFactoryManagerInterface;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPayment;

/**
 * Class PaymentManager
 *
 * @package UmberFirm\Bundle\PaymentBundle\Component\Manager
 */
class PaymentManager implements PaymentManagerInterface
{
    /**
     * @var PaymentFactoryManagerInterface
     */
    private $paymentFactoryManager;

    /**
     * PaymentManager constructor.
     *
     * @param PaymentFactoryManagerInterface $paymentFactoryManager
     */
    public function __construct(PaymentFactoryManagerInterface $paymentFactoryManager)
    {
        $this->paymentFactoryManager = $paymentFactoryManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getPayment(ShopPayment $shopPayment): ?PaymentInterface
    {
        $paymentFactory = $this->paymentFactoryManager->getFactory($shopPayment->getPayment()->getCode());
        if (null === $paymentFactory) {
            return null;
        }

        return $paymentFactory->createPayment($shopPayment->getSettings());
    }

    /**
     * {@inheritdoc}
     */
    public function generatePaymentUrl(ShopPayment $shopPayment, Order $order): ?string
    {
        $payment = $this->getPayment($shopPayment);
        if (null === $payment) {
            return null;
        }

        $productsName = [];
        $productsCount = [];
        $productsPrice = [];

        foreach ($order->getOrderItems() as $item) {
            $productsName[] = $item->getProductVariant()->getProduct()->getName();
            $productsCount[] = $item->getQuantity();
            $productsPrice[] = $item->getProductVariant()->getSalePrice();
        }

        return $payment->generatePaymentUrl([
            'orderReference' => $order->getNumber(),
            'orderDate' => time(),
            'amount' => $order->getAmount(),
            'currency' => $order->getShopCurrency()->getCurrency()->getCode(),
            'productName' => $productsName,
            'productCount' => $productsCount,
            'productPrice' => $productsPrice,
        ]);
    }
}
