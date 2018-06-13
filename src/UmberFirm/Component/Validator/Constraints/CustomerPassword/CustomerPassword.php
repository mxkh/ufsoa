<?php

declare(strict_types=1);

namespace UmberFirm\Component\Validator\Constraints\CustomerPassword;

use Symfony\Component\Validator\Constraint;

/**
 * Class CustomerPassword
 *
 * @package UmberFirm\Component\Validator\Constraints\CustomerPassword
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class CustomerPassword extends Constraint
{
    /**
     * @var string
     */
    public $message = 'This value should be the user\'s current password.';

    /**
     * @var string
     */
    public $service = 'security.validator.customer_password';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return $this->service;
    }
}
