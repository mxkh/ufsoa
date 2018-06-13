<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Customer\DataTransferObject\Factory;

use UmberFirm\Bundle\PublicBundle\Component\Customer\DataTransferObject\CustomerConfirmationCodeInterface;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Component\DataTransferObject\Factory\DataTransferObjectFactoryInterface;

/**
 * Interface CustomerDataTransferObjectFactoryInterface
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Customer\DataTransferObject\Factory
 */
interface CustomerDataTransferObjectFactoryInterface extends DataTransferObjectFactoryInterface
{
    /**
     * @param Customer $customer
     *
     * @return CustomerConfirmationCodeInterface
     */
    public function createCustomerConfirmationCode(Customer $customer): CustomerConfirmationCodeInterface;
}
