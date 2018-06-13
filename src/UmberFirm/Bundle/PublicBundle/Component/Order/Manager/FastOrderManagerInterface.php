<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Order\Manager;

use UmberFirm\Bundle\PublicBundle\Component\Order\DataTransferObject\FastOrderDTOInterface;

/**
 * Interface FastOrderManagerInterface
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Order\Manager
 */
interface FastOrderManagerInterface
{
    /**
     * @param FastOrderDTOInterface $fastOrderDTO
     *
     * @return bool
     */
    public function manage(FastOrderDTOInterface $fastOrderDTO): bool;
}
