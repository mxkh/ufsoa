<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Filter;

use Elastica\Filter\AbstractFilter;

/**
 * Interface NestedFilterInterface
 *
 * @package UmberFirm\Component\Catalog\Filter
 */
interface NestedFilterInterface
{
    /**
     * @return AbstractFilter
     */
    public function getFilter(): AbstractFilter;
}
