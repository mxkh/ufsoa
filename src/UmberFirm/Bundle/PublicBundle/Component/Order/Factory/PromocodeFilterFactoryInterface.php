<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Order\Factory;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\Promocode;
use UmberFirm\Bundle\PublicBundle\Component\Order\Filter\PromocodeFilterInterface;

/**
 * Interface PromocodeFilterFactoryInterface
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Order\Factory
 */
interface PromocodeFilterFactoryInterface
{
    /**
     * @param Promocode $promocode
     * @param null|Customer $customer
     *
     * @return PromocodeFilterInterface
     */
    public function createFilter(Promocode $promocode, ?Customer $customer): PromocodeFilterInterface;
}
