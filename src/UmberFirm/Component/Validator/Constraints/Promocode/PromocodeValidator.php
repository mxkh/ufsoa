<?php

declare(strict_types=1);

namespace UmberFirm\Component\Validator\Constraints\Promocode;

use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use UmberFirm\Bundle\PublicBundle\Component\Order\Manager\PromocodeManagerInterface;

/**
 * Class PromocodeValidator
 *
 * @package UmberFirm\Component\Validator\Constraints\Promocode
 */
class PromocodeValidator extends ConstraintValidator
{
    /**
     * @var PromocodeManagerInterface
     */
    private $promocodeManager;

    /**
     * PublicOrderType constructor.
     *
     * @param PromocodeManagerInterface $promocodeManager
     */
    public function __construct(PromocodeManagerInterface $promocodeManager)
    {
        $this->promocodeManager = $promocodeManager;
    }

    /**
     * @param PromocodeInterface $object
     * @param Promocode $constraint
     *
     * {@inheritdoc}
     */
    public function validate($object, Constraint $constraint)
    {
        if (false === ($constraint instanceof Promocode)) {
            throw new UnexpectedTypeException($constraint, Promocode::class);
        }

        if (false === ($object instanceof PromocodeInterface)) {
            throw new UnexpectedTypeException($constraint, PromocodeInterface::class);
        }

        if (null === $object->getPromocode()) {
            return;
        }

        $errorPath = null !== $constraint->errorPath ? $constraint->errorPath : $constraint->promocode;

        if (false === $this->promocodeManager->verify($object->getPromocode(), $object->getCustomer())) {
            $this->context->buildViolation($constraint->message)
                ->atPath($errorPath)
                ->addViolation();
        }
    }
}
