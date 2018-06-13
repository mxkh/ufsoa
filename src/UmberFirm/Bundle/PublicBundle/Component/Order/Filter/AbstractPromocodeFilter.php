<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Order\Filter;

/**
 * Class AbstractPromocodeFilter
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Order\Filter
 */
abstract class AbstractPromocodeFilter
{
    /**
     * @var bool
     */
    protected $result = true;

    /**
     * {@inheritdoc}
     */
    public function getResult(): bool
    {
        return $this->result;
    }
}
