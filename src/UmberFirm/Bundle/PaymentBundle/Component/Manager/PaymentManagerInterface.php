<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PaymentBundle\Component\Manager;

use UmberFirm\Bundle\OrderBundle\Entity\Order;
use UmberFirm\Bundle\PaymentBundle\Component\Adapter\PaymentInterface;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPayment;

/**
 * Interface PaymentManagerInterface
 *
 * @package UmberFirm\Bundle\PaymentBundle\Component\Manager
 */
interface PaymentManagerInterface
{
    /**
     * @param ShopPayment $shopPayment
     *
     * @return null|PaymentInterface
     */
    public function getPayment(ShopPayment $shopPayment): ?PaymentInterface;

    /**
     * @param ShopPayment $shopPayment
     * @param Order $order
     *
     * @return null|string
     */
    public function generatePaymentUrl(ShopPayment $shopPayment, Order $order): ?string;
}
