<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Query;

use Elastica\Aggregation\AbstractAggregation;

/**
 * Interface AggregationInterface
 *
 * @package UmberFirm\Component\Catalog\Query
 */
interface AggregationInterface
{
    const AGGREGATION_SIZE = 2147483647;
    const DEFAULT_AGG_NAME = 'agg_string_facet';
    const DEFAULT_FACET_KEY_NAME = 'facet_key';
    const DEFAULT_FACET_KEY_VALUE = 'facet_value';

    /**
     * @return AbstractAggregation
     */
    public function getAggregation(): AbstractAggregation;
}
