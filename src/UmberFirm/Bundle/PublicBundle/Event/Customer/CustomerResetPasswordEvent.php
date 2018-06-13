<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Event\Customer;

use Symfony\Component\EventDispatcher\Event;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;

/**
 * Class CustomerResetPasswordEvent
 *
 * @package UmberFirm\Bundle\PublicBundle\Event\Customer
 */
class CustomerResetPasswordEvent extends Event implements CustomerResetPasswordEventInterface
{
    /**
     * @var Customer
     */
    protected $customer;

    /**
     * CustomerEvent constructor.
     *
     * @param Customer $customer
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }
}
