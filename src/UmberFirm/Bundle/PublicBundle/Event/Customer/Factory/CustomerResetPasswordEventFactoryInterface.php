<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Event\Customer\Factory;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\PublicBundle\Event\Customer\CustomerResetPasswordEventInterface;

/**
 * Interface CustomerResetPasswordEventFactoryInterface
 *
 * @package UmberFirm\Bundle\PublicBundle\Event\Customer\Factory
 */
interface CustomerResetPasswordEventFactoryInterface
{
    /**
     * @param Customer $customer
     *
     * @return CustomerResetPasswordEventInterface
     */
    public function createCustomerResetPasswordEvent(Customer $customer): CustomerResetPasswordEventInterface;
}
