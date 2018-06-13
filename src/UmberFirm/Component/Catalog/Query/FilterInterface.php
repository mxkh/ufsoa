<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Query;

use Elastica\Filter\AbstractFilter;

/**
 * Interface FilterInterface
 *
 * @package UmberFirm\Component\Catalog\Query
 */
interface FilterInterface
{
    /**
     * @return AbstractFilter
     */
    public function getFilter(): AbstractFilter;
}
