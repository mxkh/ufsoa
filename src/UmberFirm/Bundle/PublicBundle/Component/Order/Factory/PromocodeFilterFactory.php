<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Order\Factory;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\Promocode;
use UmberFirm\Bundle\PublicBundle\Component\Order\Filter\PromocodeFilter;
use UmberFirm\Bundle\PublicBundle\Component\Order\Filter\PromocodeFilterInterface;

/**
 * Class PromocodeFilterFactory
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Order\Factory
 */
class PromocodeFilterFactory implements PromocodeFilterFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createFilter(Promocode $promocode, ?Customer $customer): PromocodeFilterInterface
    {
        return new PromocodeFilter($promocode, $customer);
    }
}
