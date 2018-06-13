<?php

declare(strict_types=1);

namespace UmberFirm\Component\Validator\Constraints\Promocode;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\Promocode;

/**
 * Interface PromocodeInterface
 *
 * @package UmberFirm\Component\Validator\Constraints\Promocode
 */
interface PromocodeInterface
{
    /**
     * @return null|Customer
     */
    public function getCustomer(): ?Customer;

    /**
     * @return null|Promocode
     */
    public function getPromocode(): ?Promocode;
}
