<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Event\Customer;

use Symfony\Component\EventDispatcher\Event;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;

/**
 * Class CustomerEvent
 *
 * @package UmberFirm\Bundle\PublicBundle\Event\Customer
 */
class CustomerEvent extends Event implements CustomerEventInterface
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
