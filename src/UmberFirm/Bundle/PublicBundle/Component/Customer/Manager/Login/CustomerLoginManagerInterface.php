<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Customer\Manager\Login;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Interface LoginManagerInterface
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Customer\Manager\Login
 */
interface CustomerLoginManagerInterface
{
    /**
     * @param Customer $customer
     */
    public function login(Customer $customer): void;

    /**
     * @param Customer $customer
     * @param $password
     *
     * @return bool
     */
    public function checkCustomerPassword(Customer $customer, $password): bool;

    /**
     * @param null|string $phone
     * @param null|string $email
     * @param Shop $shop
     *
     * @return null|Customer
     */
    public function loadCustomer(?string $phone, ?string $email, Shop $shop): ?Customer;
}
