<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Customer;

use UmberFirm\Bundle\CustomerBundle\Repository\CustomerRepository;
use UmberFirm\Bundle\PublicBundle\Component\Customer\Generator\ConfirmationCodeGeneratorInterface;

/**
 * Class PasswordTrait
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Customer
 */
trait PasswordTrait
{
    /**
     * @param string $modify
     *
     * @return \DateTime
     */
    public function getPasswordExpiration(string $modify): \DateTime
    {
        $date = new \DateTime();
        $date->modify($modify);

        return $date;
    }

    /**
     * @param CustomerRepository $customerRepository
     * @param ConfirmationCodeGeneratorInterface $resetPasswordCodeGenerator
     *
     * @return string
     */
    public function generateResetPasswordCode(
        CustomerRepository $customerRepository,
        ConfirmationCodeGeneratorInterface $resetPasswordCodeGenerator
    ): string {
        $resetPasswordCode = $resetPasswordCodeGenerator->generate();
        $customer = $customerRepository->findOneBy(['resetPasswordCode' => $resetPasswordCode]);

        if (null !== $customer) {
            $this->generateResetPasswordCode($customerRepository, $resetPasswordCodeGenerator);
        }

        return $resetPasswordCode;
    }
}
