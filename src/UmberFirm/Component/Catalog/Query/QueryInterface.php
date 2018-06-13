<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Query;

use Elastica\Query\AbstractQuery;

/**
 * Interface QueryInterface
 *
 * @package UmberFirm\Component\Catalog\Query
 */
interface QueryInterface
{
    /**
     * @return AbstractQuery
     */
    public function getQuery(): AbstractQuery;
}
