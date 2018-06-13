<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Component\Generator;

use Hackzilla\PasswordGenerator\Generator\HybridPasswordGenerator;

/**
 * Class PromoCodeGenerator
 *
 * @package UmberFirm\Bundle\OrderBundle\Component\Generator
 */
class PromoCodeGenerator implements PromoCodeGeneratorInterface
{
    /**
     * @var HybridPasswordGenerator
     */
    protected $generator;

    /**
     * PromoCodeGenerator constructor.
     *
     * @param HybridPasswordGenerator $generator
     */
    public function __construct(HybridPasswordGenerator $generator)
    {
        $this->generator = $this->initialize($generator);
    }

    /**
     * @param HybridPasswordGenerator $generator
     *
     * @return HybridPasswordGenerator
     */
    protected function initialize(HybridPasswordGenerator $generator): HybridPasswordGenerator
    {
        $generator->setUppercase(true);
        $generator->setLowercase(false);
        $generator->setSymbols(false);
        $generator->setNumbers(true);

        return $generator;
    }

    /**
     * {@inheritdoc}
     */
    public function setSegmentCount(int $count): PromoCodeGeneratorInterface
    {
        $this->generator->setSegmentCount($count);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setSegmentLength(int $length): PromoCodeGeneratorInterface
    {
        $this->generator->setSegmentLength($length);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(): string
    {
        return (string) $this->generator->generatePassword();
    }
}
