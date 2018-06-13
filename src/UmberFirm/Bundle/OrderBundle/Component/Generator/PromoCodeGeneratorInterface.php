<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Component\Generator;

/**
 * Interface PromoCodeGeneratorInterface
 *
 * @package UmberFirm\Bundle\OrderBundle\Component\Generator
 */
interface PromoCodeGeneratorInterface
{
    /**
     * @param int $count
     *
     * @return PromoCodeGeneratorInterface
     *
     * @throws \InvalidArgumentException
     */
    public function setSegmentCount(int $count): PromoCodeGeneratorInterface;

    /**
     * @param int $length
     *
     * @return PromoCodeGeneratorInterface
     *
     * @throws \InvalidArgumentException
     */
    public function setSegmentLength(int $length): PromoCodeGeneratorInterface;

    /**
     * Generate promo code.
     *
     * @return string
     */
    public function generate(): string;
}
