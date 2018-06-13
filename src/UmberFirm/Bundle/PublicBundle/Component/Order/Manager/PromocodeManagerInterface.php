<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Order\Manager;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\Promocode;

/**
 * Interface PromocodeVerificationManagerInterface
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Order\Service
 */
interface PromocodeManagerInterface
{
    /**
     * @param Promocode $promocode
     * @param null|Customer $customer
     *
     * @return bool
     */
    public function verify(Promocode $promocode, ?Customer $customer): bool;

    /**
     * @param Promocode $promocode
     * @param float $amount
     *
     * @return float
     */
    public function calculate(Promocode $promocode, float $amount): float;

    /**
     * @param Promocode $promocode
     *
     * @return Promocode
     */
    public function incrementUsed(Promocode $promocode): Promocode;
}
