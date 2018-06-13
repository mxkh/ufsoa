<?php

declare(strict_types=1);

namespace UmberFirm\Component\Validator\Constraints\CustomerPassword;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;

/**
 * Class CustomerPasswordValidator
 *
 * @package UmberFirm\Component\Validator\Constraints
 */
class CustomerPasswordValidator extends ConstraintValidator
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * CustomerPasswordValidator constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(TokenStorageInterface $tokenStorage, EncoderFactoryInterface $encoderFactory)
    {
        $this->tokenStorage = $tokenStorage;
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($password, Constraint $constraint): void
    {
        if (false === ($constraint instanceof CustomerPassword)) {
            throw new UnexpectedTypeException($constraint, CustomerPassword::class);
        }

        /** @var Customer $user */
        $user = $this->tokenStorage->getToken()->getUser()->getCustomer();

        if (false === ($user instanceof Customer)) {
            throw new ConstraintDefinitionException('The User object must implement the Customer interface.');
        }

        $encoder = $this->encoderFactory->getEncoder($user);

        if (null === $password && null === $user->getPassword()) {
            return;
        }

        if (false === $encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt())) {
            $this->context->addViolation($constraint->message);
        }
    }
}
