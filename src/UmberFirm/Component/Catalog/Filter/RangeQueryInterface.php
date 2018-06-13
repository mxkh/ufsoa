<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Filter;

/**
 * Interface RangeQueryInterface
 *
 * @package UmberFirm\Component\Catalog\Filter
 */
interface RangeQueryInterface extends NestedQueryInterface
{
    /**
     * @return int
     */
    public function getMin(): int;

    /**
     * @return int
     */
    public function getMax(): int;
}
