<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Order\DataTransferObject\Factory;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\FastOrder;
use UmberFirm\Bundle\PublicBundle\Component\Order\DataTransferObject\FastOrderDTOInterface;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Interface OrderDataTransferObjectFactoryInterface
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Order\DataTransferObject\Factory
 */
interface OrderDataTransferObjectFactoryInterface
{
    /**
     * @param FastOrder $fastOrder
     * @param Shop $shop
     * @param Customer $customer
     *
     * @return FastOrderDTOInterface
     */
    public function createFastOrderDTO(FastOrder $fastOrder, Shop $shop, Customer $customer): FastOrderDTOInterface;
}
