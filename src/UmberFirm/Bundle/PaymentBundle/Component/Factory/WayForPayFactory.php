<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PaymentBundle\Component\Factory;

use Symfony\Component\Routing\RouterInterface;
use UmberFirm\Bundle\PaymentBundle\Component\Adapter\PaymentInterface;
use UmberFirm\Bundle\PaymentBundle\Component\Adapter\WayForPayAdapter;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPaymentSettings;

/**
 * Class WayForPayFactory
 *
 * @package UmberFirm\Bundle\PaymentBundle\Component\Factory
 */
final class WayForPayFactory extends AbstractPaymentFactory
{
    /**
     * {@inheritdoc}
     */
    public function createPayment(ShopPaymentSettings $paymentSettings): PaymentInterface
    {
        $sdk = new \WayForPay($paymentSettings->getPublicKey(), $paymentSettings->getPrivateKey());

        return new WayForPayAdapter($sdk, $paymentSettings, $this->router);
    }
}
