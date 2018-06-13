<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Customer\Producer;

use OldSound\RabbitMqBundle\RabbitMq\Producer;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\PublicBundle\Component\Customer\DataTransferObject\CustomerConfirmationCodeInterface;

/**
 * Class CustomerProducer
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Customer\Producer
 */
class PasswordProducer extends Producer implements PasswordProducerInterface
{
    /**
     * {@inheritdoc}
     */
    public function sendResetPassword(Customer $customer): void
    {
        $messageBody = serialize($customer);
        $this->publish($messageBody/*, 'confirmation-code'*/);
    }
}
