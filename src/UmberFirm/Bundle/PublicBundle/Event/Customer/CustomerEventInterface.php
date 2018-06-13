<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Event\Customer;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;

/**
 * Interface CustomerEventInterface
 *
 * @package UmberFirm\Bundle\PublicBundle\Event\Customer
 */
interface CustomerEventInterface
{
    const SIGN_UP = 'customer.sign_up';
    const CONFIRM_CUSTOMER = 'customer.confirm_customer';

    /**
     * @return Customer
     */
    public function getCustomer(): Customer;
}
