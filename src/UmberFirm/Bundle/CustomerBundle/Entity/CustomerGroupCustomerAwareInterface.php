<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * Interface CustomerGroupCustomerAwareInterface
 *
 * @package UmberFirm\Bundle\CustomerBundle\Entity
 */
interface CustomerGroupCustomerAwareInterface
{
    /**
     * @return Collection|Customer[]
     */
    public function getCustomers(): Collection;

    /**
     * @param Customer $customer
     *
     * @return CustomerGroup
     */
    public function addCustomer(Customer $customer): CustomerGroup;

    /**
     * @param Customer $customer
     *
     * @return CustomerGroup
     */
    public function removeCustomer(Customer $customer): CustomerGroup;
}
