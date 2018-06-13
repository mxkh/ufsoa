<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * Interface CustomerAddressAwareInterface
 *
 * @package UmberFirm\Bundle\CustomerBundle\Entity
 */
interface CustomerAddressAwareInterface
{
    /**
     * @return Collection|CustomerAddress[]
     */
    public function getCustomerAddresses(): Collection;

    /**
     * @param CustomerAddress $address
     *
     * @return Customer
     */
    public function addCustomerAddress(CustomerAddress $address): Customer;

    /**
     * @param CustomerAddress $address
     *
     * @return Customer
     */
    public function removeCustomerAddress(CustomerAddress $address): Customer;
}
