<?php

namespace UmberFirm\Bundle\OrderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Interface OrderAwareInterface
 *
 * @package UmberFirm\Bundle\OrderBundle\Entity
 */
interface OrderItemAwareInterface
{
    /**
     * @return ArrayCollection|OrderItem[]
     */
    public function getOrderItems();

    /**
     * @param OrderItem $orderItem
     *
     * @return $this
     */
    public function addOrderItem(OrderItem $orderItem);

    /**
     * @param OrderItem $orderItem
     *
     * @return $this
     */
    public function removeOrderItem(OrderItem $orderItem);
}
