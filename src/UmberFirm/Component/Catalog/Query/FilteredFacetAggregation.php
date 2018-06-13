<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Query;

use Elastica\Aggregation\AbstractAggregation;
use Elastica\Aggregation\Filter;
use Elastica\Aggregation\Nested;
use Elastica\Aggregation\Terms;
use Elastica\Filter\Term;
use UmberFirm\Component\Catalog\Elastica\Filter\BoolFilter;

/**
 * Class FilteredFacetAggregation
 *
 * @package UmberFirm\Component\Catalog\Query
 */
final class FilteredFacetAggregation implements AggregationInterface
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var BoolFilter
     */
    private $filter;

    /**
     * FilteredFacetAggregation constructor.
     *
     * @param string $name
     * @param BoolFilter $filter
     */
    public function __construct(string $name, BoolFilter $filter)
    {
        $this->name = $name;
        $this->filter = $this->normalizeFilter(clone $filter, $name);
    }

    /**
     * {@inheritdoc}
     */
    public function getAggregation(): AbstractAggregation
    {
        $facetValueAggregation = new Terms(self::DEFAULT_FACET_KEY_VALUE);
        $facetValueAggregation
            ->setField('search_data.string_facets.value')
            ->setOrder('_count', 'desc')
            ->setSize(self::AGGREGATION_SIZE);

        $facetFieldTerm = new Term();
        $facetFieldTerm->setTerm('search_data.string_facets.key', $this->name);

        $facetFieldAggregation = new Filter(self::DEFAULT_FACET_KEY_NAME, $facetFieldTerm);
        $facetFieldAggregation->addAggregation($facetValueAggregation);

        $aggregation = new Nested($this->getAggName(), 'search_data.string_facets');
        $aggregation->addAggregation($facetFieldAggregation);

        $aggregationFilter = new Filter($this->name, $this->filter);
        $aggregationFilter->addAggregation($aggregation);

        return $aggregationFilter;
    }

    /**
     * @param BoolFilter $filter
     * @param string $name
     *
     * @return BoolFilter
     */
    private function normalizeFilter(BoolFilter $filter, string $name): BoolFilter
    {
        return $filter->removeFilterFromMust(\Elastica\Filter\Nested::class, $name);
    }

    /**
     * @return string
     */
    private function getAggName(): string
    {
        return (string) sprintf('%s_%s', $this->name, 'facet');
    }
}
