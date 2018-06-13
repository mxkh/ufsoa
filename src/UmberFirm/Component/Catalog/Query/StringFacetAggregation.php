<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Query;

use Elastica\Aggregation\AbstractAggregation;
use Elastica\Aggregation\Nested;
use Elastica\Aggregation\Terms;

/**
 * Class StringFacetAggregation
 *
 * @package UmberFirm\Component\Catalog\Query
 */
final class StringFacetAggregation implements AggregationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getAggregation(): AbstractAggregation
    {
        $aggregation = new Nested(self::DEFAULT_AGG_NAME, 'search_data.string_facets');
        $facetFieldAggregation = new Terms(self::DEFAULT_FACET_KEY_NAME);
        $facetFieldAggregation->setField('search_data.string_facets.key');
        $facetValueAggregation = new Terms(self::DEFAULT_FACET_KEY_VALUE);
        $facetValueAggregation
            ->setField('search_data.string_facets.value')
            ->setOrder('_count', 'desc')
            ->setSize(self::AGGREGATION_SIZE);
        $facetFieldAggregation->addAggregation($facetValueAggregation);
        $aggregation->addAggregation($facetFieldAggregation);

        return $aggregation;
    }
}
