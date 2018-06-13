<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Event\Customer;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;

/**
 * Interface CustomerResetPasswordEventInterface
 *
 * @package UmberFirm\Bundle\PublicBundle\Event\Customer
 */
interface CustomerResetPasswordEventInterface
{
    const RESET_PASSWORD = 'customer.reset_password';
    const CHANGE_PASSWORD = 'customer.change_password';
    const CONFIRM_RESET_PASSWORD = 'customer.confirm_reset_password';

    /**
     * @return Customer
     */
    public function getCustomer(): Customer;
}
