<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Customer\Producer;

use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use UmberFirm\Bundle\PublicBundle\Component\Customer\DataTransferObject\CustomerConfirmationCodeInterface;

/**
 * Interface CustomerProducerInterface
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Customer\Producer
 */
interface CustomerProducerInterface extends ProducerInterface
{
    /**
     * @param CustomerConfirmationCodeInterface $confirmationCode
     */
    public function sendConfirmationCode(CustomerConfirmationCodeInterface $confirmationCode): void;
}
