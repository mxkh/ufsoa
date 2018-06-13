<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsIsBarCodeExist extends Constraint
{
    /**
     * @var string
     */
    public $message = 'The code "%string%" is not exist.';
    
    /**
     * @return string
     */
    public function validatedBy()
    {
        return 'is_barCode_exist.validator';
    }
}
