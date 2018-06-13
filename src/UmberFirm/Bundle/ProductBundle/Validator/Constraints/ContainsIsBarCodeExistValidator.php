<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Validator\Constraints;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use UmberFirm\Bundle\ProductBundle\Entity\Department;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class ContainsIsBarCodeExistValidator extends ConstraintValidator 
{
    /**
     *
     * @var EntityManager 
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint) {
        
        if (true === is_null($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%string%', $value)
                ->addViolation();
        } else {
            $department = $this->entityManager
                ->getRepository(Department::class)
                ->findByBarcode($value);
            
            if (false === ($department instanceof Department)) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('%string%', $value)
                    ->addViolation();
            }
        }
    }
}
