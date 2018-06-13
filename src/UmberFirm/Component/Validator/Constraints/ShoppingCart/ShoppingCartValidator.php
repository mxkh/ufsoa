<?php

declare(strict_types=1);

namespace UmberFirm\Component\Validator\Constraints\ShoppingCart;

use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class ShoppingCartValidator
 *
 * @package UmberFirm\Component\Validator\Constraints\ShoppingCart
 */
class ShoppingCartValidator extends ConstraintValidator
{
    /**
     * @param ShoppingCartInterface $object
     * @param ShoppingCart $constraint
     *
     * {@inheritdoc}
     */
    public function validate($object, Constraint $constraint)
    {
        if (false === ($constraint instanceof ShoppingCart)) {
            throw new UnexpectedTypeException($constraint, ShoppingCart::class);
        }

        if (false === ($object instanceof ShoppingCartInterface)) {
            throw new UnexpectedTypeException($constraint, ShoppingCartInterface::class);
        }

        $errorPath = null !== $constraint->errorPath ? $constraint->errorPath : $constraint->shoppingCart;

        if (null === $object->getShoppingCart()) {
            $this->context->buildViolation(sprintf($constraint->messageNull,'ShoppingCart'))
                ->addViolation();

            return;
        }

        if (null === $object->getShop()) {
            $this->context->buildViolation(sprintf($constraint->messageNull,'Shop'))
                ->addViolation();

            return;
        }

        if (null === $object->getShoppingCart()->getShop()) {
            $this->context->buildViolation($constraint->messageInvalid)
                ->atPath($errorPath)
                ->addViolation();

            return;
        }

        $isShop = $object->getShoppingCart()->getShop()->getId() === $object->getShop()->getId();

        if (false === $isShop) {
            $this->context->buildViolation($constraint->messageInvalid)
                ->atPath($errorPath)
                ->addViolation();
        }


        if (null === $object->getCustomer()) {
            return;
        }

        if (null === $object->getShoppingCart()->getCustomer()) {
            return;
        }

        $isCustomer = $object->getShoppingCart()->getCustomer()->getId() === $object->getCustomer()->getId();

        if (false === $isCustomer) {
            $this->context->buildViolation($constraint->messageInvalid)
                ->atPath($errorPath)
                ->addViolation();
        }
    }
}
