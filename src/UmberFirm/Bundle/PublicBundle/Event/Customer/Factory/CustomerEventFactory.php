<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Event\Customer\Factory;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\PublicBundle\Event\Customer\CustomerEvent;
use UmberFirm\Bundle\PublicBundle\Event\Customer\CustomerEventInterface;

/**
 * Class CustomerEventFactory
 *
 * @package UmberFirm\Bundle\PublicBundle\Event\Customer\Factory
 */
class CustomerEventFactory implements CustomerEventFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createCustomerEvent(Customer $customer): CustomerEventInterface
    {
        return new CustomerEvent($customer);
    }
}
