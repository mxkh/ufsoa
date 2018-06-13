<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Event\Customer\Factory;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\PublicBundle\Event\Customer\CustomerEventInterface;

/**
 * Interface CustomerEventFactoryInterface
 *
 * @package UmberFirm\Bundle\PublicBundle\Event\Customer\Factory
 */
interface CustomerEventFactoryInterface
{
    /**
     * @param Customer $customer
     *
     * @return CustomerEventInterface
     */
    public function createCustomerEvent(Customer $customer): CustomerEventInterface;
}
