<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Customer\Manager\SignUp;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;

/**
 * Interface CustomerSignUpManagerInterface
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Customer\Manager\SignUp
 */
interface CustomerSignUpManagerInterface
{
    /**
     * @param Customer $customer
     */
    public function complete(Customer $customer): void;

    /**
     * @param Customer $customer
     *
     * @return Customer
     */
    public function save(Customer $customer): Customer;

    /**
     * @param Customer $customer
     * @param string $email
     *
     * @return Customer
     */
    public function addEmail(Customer $customer, string $email): Customer;
}
