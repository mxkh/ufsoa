<?php

declare(strict_types=1);

namespace UmberFirm\Component\Doctrine\Entity;

/**
 * Interface StringableInterface.
 *
 * @package UmberFirm\Component\Doctrine\Entity
 */
interface StringableInterface
{
    /**
     * @return string
     *
     * TODO: Add string return type when CustomerBundle will be refactored
     */
    public function __toString();
}
