<?php

declare(strict_types=1);

namespace UmberFirm\Component\Doctrine\Uuid;

use Ramsey\Uuid\UuidInterface;

/**
 * Interface UuidInterface
 *
 * @package UmberFirm\Component\Doctrine\Uuid
 */
interface UuidEntityInterface
{
    /**
     * @return null|UuidInterface
     */
    public function getId(): ?UuidInterface;
}
