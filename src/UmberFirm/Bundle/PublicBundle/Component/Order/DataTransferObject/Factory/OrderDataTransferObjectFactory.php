<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Order\DataTransferObject\Factory;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\FastOrder;
use UmberFirm\Bundle\PublicBundle\Component\Order\DataTransferObject\FastOrderDTO;
use UmberFirm\Bundle\PublicBundle\Component\Order\DataTransferObject\FastOrderDTOInterface;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class OrderDataTransferObjectFactory
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Order\DataTransferObject\Factory
 */
class OrderDataTransferObjectFactory implements OrderDataTransferObjectFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createFastOrderDTO(FastOrder $fastOrder, Shop $shop, Customer $customer): FastOrderDTOInterface
    {
        return new FastOrderDTO($fastOrder, $shop, $customer);
    }
}
