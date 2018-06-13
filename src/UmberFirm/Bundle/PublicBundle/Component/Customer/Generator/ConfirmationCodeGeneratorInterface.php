<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Customer\Generator;

/**
 * Interface ConfirmationCodeGeneratorInterface
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Customer\Generator
 */
interface ConfirmationCodeGeneratorInterface
{
    /**
     * @param int $length
     *
     * @return ConfirmationCodeGeneratorInterface
     */
    public function setLength($length): ConfirmationCodeGeneratorInterface;

    /**
     * Generate confirmation code.
     *
     * @return string confirmation code
     */
    public function generate(): string;
}
