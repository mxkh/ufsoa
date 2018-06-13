<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PaymentBundle\Component\Factory;

use Symfony\Component\Routing\RouterInterface;
use UmberFirm\Bundle\PaymentBundle\Component\Adapter\PaymentInterface;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPaymentSettings;

/**
 * Class SDKFactory
 *
 * @package UmberFirm\Bundle\PaymentBundle\Component\Factory
 */
abstract class AbstractPaymentFactory
{
    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * AbstractPaymentFactory constructor.
     *
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param ShopPaymentSettings $paymentSettings
     *
     * @return PaymentInterface
     */
    abstract public function createPayment(ShopPaymentSettings $paymentSettings): PaymentInterface;
}
