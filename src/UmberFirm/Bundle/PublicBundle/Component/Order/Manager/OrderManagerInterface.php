<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Order\Manager;

use UmberFirm\Bundle\OrderBundle\Entity\Order;
use UmberFirm\Bundle\PublicBundle\DataObject\PublicOrder;

/**
 * Interface OrderManagerInterface
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Order\Manager
 */
interface OrderManagerInterface
{
    /**
     * @param PublicOrder $publicOrder
     *
     * @return PublicOrder
     */
    public function manage(PublicOrder $publicOrder): PublicOrder;


    /**
     * @param PublicOrder $publicOrder
     *
     * @return Order
     */
    public function buildOrder(PublicOrder $publicOrder): Order;

    /**
     * @param PublicOrder $publicOrder
     * @param Order $order
     *
     * @return PublicOrder
     */
    public function prepareOrderPublic(PublicOrder $publicOrder, Order $order): PublicOrder;
}
