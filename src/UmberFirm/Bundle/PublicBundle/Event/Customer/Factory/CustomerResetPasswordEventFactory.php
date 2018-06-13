<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Event\Customer\Factory;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\PublicBundle\Event\Customer\CustomerResetPasswordEvent;
use UmberFirm\Bundle\PublicBundle\Event\Customer\CustomerResetPasswordEventInterface;

/**
 * Class CustomerResetPasswordEventFactory
 *
 * @package UmberFirm\Bundle\PublicBundle\Event\Customer\Factory
 */
class CustomerResetPasswordEventFactory implements CustomerResetPasswordEventFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createCustomerResetPasswordEvent(Customer $customer): CustomerResetPasswordEventInterface
    {
        return new CustomerResetPasswordEvent($customer);
    }
}
