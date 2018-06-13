<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Customer\Producer;

use OldSound\RabbitMqBundle\RabbitMq\Producer;
use UmberFirm\Bundle\PublicBundle\Component\Customer\DataTransferObject\CustomerConfirmationCodeInterface;

/**
 * Class CustomerProducer
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Customer\Producer
 */
class CustomerProducer extends Producer implements CustomerProducerInterface
{
    /**
     * {@inheritdoc}
     */
    public function sendConfirmationCode(CustomerConfirmationCodeInterface $confirmationCode): void
    {
        $messageBody = serialize($confirmationCode);
        $this->publish($messageBody/*, 'confirmation-code'*/);
    }
}
