<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Entity;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\OrderBundle\Entity\Order;

/**
 * Interface CustomerOrderAwareInterface
 *
 * @package UmberFirm\Bundle\CustomerBundle\Entity
 */
interface CustomerOrderAwareInterface
{
    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection;

    /**
     * @param Order $order
     *
     * @return Customer
     */
    public function addOrder(Order $order): Customer;

    /**
     * @param Order $order
     *
     * @return Customer
     */
    public function removeOrder(Order $order): Customer;
}
