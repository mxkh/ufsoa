<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Customer\DataTransferObject;

use Ramsey\Uuid\UuidInterface;
use UmberFirm\Component\DataTransferObject\DataTransferObjectInterface;

/**
 * Interface CustomerConfirmationCodeInterface
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Customer\DataTransferObject
 */
interface CustomerConfirmationCodeInterface extends DataTransferObjectInterface
{
    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface;

    /**
     * @return null|string
     */
    public function getPhone(): ?string;

    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @return null|string
     */
    public function getEmail(): ?string;
}
