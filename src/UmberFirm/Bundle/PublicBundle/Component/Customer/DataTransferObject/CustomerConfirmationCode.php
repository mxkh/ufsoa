<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Customer\DataTransferObject;

use Ramsey\Uuid\UuidInterface;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;

/**
 * Class CustomerConfirmationCode
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Customer\DataTransferObject
 */
class CustomerConfirmationCode implements CustomerConfirmationCodeInterface
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var null|string
     */
    private $phone;

    /**
     * @var string
     */
    private $code;

    /**
     * @var null|string
     */
    private $email;

    public function __construct(Customer $customer)
    {
        $this->id = $customer->getId();
        $this->phone = $customer->getPhone();
        $this->code = $customer->getConfirmationCode();
        $this->email = $customer->getEmail();
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * {@inheritdoc}
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
}
