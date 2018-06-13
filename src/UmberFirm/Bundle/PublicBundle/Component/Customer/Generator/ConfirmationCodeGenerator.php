<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Customer\Generator;

use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;

/**
 * Class ConfirmationCodeGenerator
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Customer\Generator
 */
class ConfirmationCodeGenerator implements ConfirmationCodeGeneratorInterface
{
    /**
     * @var ComputerPasswordGenerator
     */
    protected $generator;

    /**
     * ConfirmationCodeGenerator constructor.
     *
     * @param ComputerPasswordGenerator $generator
     * @param $length
     */
    public function __construct(ComputerPasswordGenerator $generator, $length)
    {
        $this->generator = $this->initialize($generator);
        $this->setLength($length);
    }

    /**
     * @param ComputerPasswordGenerator $generator
     *
     * @return ComputerPasswordGenerator
     */
    protected function initialize(ComputerPasswordGenerator $generator): ComputerPasswordGenerator
    {
        $generator->setUppercase(false);
        $generator->setLowercase(false);
        $generator->setSymbols(false);
        $generator->setNumbers(true);

        return $generator;
    }

    /**
     * {@inheritdoc}
     */
    public function setLength($length): ConfirmationCodeGeneratorInterface
    {
        $this->generator->setLength($length);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(): string
    {
        return $this->generator->generatePassword();
    }
}
