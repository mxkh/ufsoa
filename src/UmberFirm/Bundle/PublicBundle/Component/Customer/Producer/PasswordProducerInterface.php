<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Customer\Producer;

use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;

/**
 * Interface PasswordProducerInterface
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Customer\Producer
 */
interface PasswordProducerInterface extends ProducerInterface
{
    /**
     * @param Customer $customer
     */
    public function sendResetPassword(Customer $customer): void;
}
