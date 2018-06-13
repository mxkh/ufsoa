<?php

declare(strict_types=1);

namespace UmberFirm\Component\Validator\Constraints\Promocode;

use Symfony\Component\Validator\Constraint;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;

/**
 * Class ContainsPromocode
 *
 * @package UmberFirm\Component\Validator\Constraints\Promocode
 */
class Promocode extends Constraint
{
    /**
     * @var string
     */
    public $message = 'This value is not valid.';

    /**
     * @var string
     */
    public $promocode;

    /**
     * @var null|string
     */
    public $errorPath = null;

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequiredOptions()
    {
        return ['promocode'];
    }
}
