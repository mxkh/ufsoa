<?php

declare(strict_types=1);

namespace UmberFirm\Component\Validator\Constraints\ShoppingCart;

use Symfony\Component\Validator\Constraint;

/**
 * Class ShoppingCart
 *
 * @package UmberFirm\Component\Validator\Constraints\ShoppingCart
 */
class ShoppingCart extends Constraint
{
    /**
     * @var string
     */
    public $messageInvalid = 'This value is not valid.';

    /**
     * @var string
     */
    public $messageNull = '%s value should not be null.';

    /**
     * @var string
     */
    public $shoppingCart;

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
        return ['shoppingCart'];
    }
}
