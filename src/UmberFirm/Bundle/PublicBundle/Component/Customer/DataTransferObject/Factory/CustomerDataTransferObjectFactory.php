<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Customer\DataTransferObject\Factory;

use UmberFirm\Bundle\PublicBundle\Component\Customer\DataTransferObject\CustomerConfirmationCode;
use UmberFirm\Bundle\PublicBundle\Component\Customer\DataTransferObject\CustomerConfirmationCodeInterface;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;

/**
 * Class CustomerDataTransferObjectFactory
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Customer\DataTransferObject\Factory
 */
class CustomerDataTransferObjectFactory implements CustomerDataTransferObjectFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createCustomerConfirmationCode(Customer $customer): CustomerConfirmationCodeInterface
    {
        return new CustomerConfirmationCode($customer);
    }
}
