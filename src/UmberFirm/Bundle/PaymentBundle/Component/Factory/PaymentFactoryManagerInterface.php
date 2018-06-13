<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PaymentBundle\Component\Factory;

/**
 * Interface PaymentAdapterFactoryInterface
 *
 * @package UmberFirm\Bundle\PaymentBundle\Component\Factory
 */
interface PaymentFactoryManagerInterface
{
    /**
     * @param string $code
     *
     * @return null|AbstractPaymentFactory
     */
    public function getFactory(string $code): ?AbstractPaymentFactory;
}
