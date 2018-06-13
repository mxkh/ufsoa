<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Query;

use Doctrine\Common\Collections\Collection;
use Elastica\Filter\AbstractFilter;
use UmberFirm\Component\Catalog\Elastica\Filter\BoolFilter;
use Elastica\Query;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use UmberFirm\Component\Catalog\Filter\FilterBuilder;
use UmberFirm\Component\Catalog\Filter\NestedFilterInterface;
use UmberFirm\Component\Catalog\Filter\NestedQueryInterface;

/**
 * Class QueryBuilder
 *
 * @package UmberFirm\Component\Catalog\Query
 */
final class QueryBuilder implements QueryBuilderInterface
{
    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @var SortBuilder
     */
    private $sortBuilder;

    /**
     * @var BoolQuery
     */
    private $boolQuery;

    /**
     * @var BoolFilter
     */
    private $boolFilter;

    /**
     * @var Query
     */
    private $query;

    /**
     * QueryBuilder constructor.
     *
     * @param FilterBuilder $filterBuilder
     * @param SortBuilder $sortBuilder
     */
    public function __construct(FilterBuilder $filterBuilder, SortBuilder $sortBuilder)
    {
        $this->filterBuilder = $filterBuilder;
        $this->sortBuilder = $sortBuilder;
        $this->boolQuery = new BoolQuery();
        $this->boolFilter = new BoolFilter();
        $this->query = Query::create('');
    }

    /**
     * {@inheritdoc}
     */
    public function buildQueryContext(Collection $collection): QueryBuilderInterface
    {
        if (true === $collection->isEmpty()) {
            return $this;
        }

        foreach ($this->filterBuilder->buildFacetFilterCollection($collection) as $filter) {
            $this->boolQuery->addMust($this->getFilterQueryContext($filter));
        }

        return $this;
    }

    /**
     * @param Collection $collection
     *
     * @return QueryBuilderInterface
     */
    public function buildFilterContext(Collection $collection): QueryBuilderInterface
    {
        if (true === $collection->isEmpty()) {
            return $this;
        }

        foreach ($this->filterBuilder->buildFacetFilterCollection($collection) as $filter) {
            $this->boolFilter->addMust($this->getFilterContext($filter));
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function buildSortQuery(Collection $collection): QueryBuilderInterface
    {
        if (true === $collection->isEmpty()) {
            return $this;
        }

        foreach ($this->sortBuilder->build($collection) as $sort) {
            $this->query->addSort($this->getSortQuery($sort));
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function buildQueryStringQuery(string $phrase): QueryBuilderInterface
    {
        if (true === empty($phrase)) {
            return $this;
        }

        $filter = $this->filterBuilder->buildQueryStringFilter($phrase);

        $this->boolQuery->addMust($filter->getQuery());

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilterQueryContext(NestedQueryInterface $filter): AbstractQuery
    {
        return $filter->getQuery();
    }

    /**
     * {@inheritdoc}
     */
    public function getFilterContext(NestedFilterInterface $filter): AbstractFilter
    {
        return $filter->getFilter();
    }

    /**
     * {@inheritdoc}
     */
    public function getSortQuery(SortInterface $sort): array
    {
        return [
            $sort->getField() => [
                'order' => $sort->getOrder(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery(): Query
    {
        $this->query->setQuery($this->boolQuery);

        return $this->query;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilter(): BoolFilter
    {
        return $this->boolFilter;
    }

    /**
     * {@inheritdoc}
     */
    public function addTermsQuery(array $terms): QueryBuilderInterface
    {
        foreach ($terms as $term) {
            if (true === is_array($term[key($term)])) {
                $this->boolQuery->addMust($this->filterBuilder->buildTermsQuery($term));

                continue;
            }

            $this->boolQuery->addMust($this->filterBuilder->buildTermQuery($term));
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addTermFilter(array $term): QueryBuilderInterface
    {
        if (false === empty($term)) {
            $this->boolFilter->addMust($this->filterBuilder->buildTermFilter($term));
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function buildCategoryQuery(array $categories): QueryBuilderInterface
    {
        if (false === empty($categories)) {
            $this->boolQuery->addMust($this->filterBuilder->buildCategoryQuery($categories));
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function buildCategoryFilter(array $categories): QueryBuilderInterface
    {
        if (false === empty($categories)) {
            $this->boolFilter->addMust($this->filterBuilder->buildCategoryFilter($categories));
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function buildDefaultAggregationalQuery(): QueryBuilderInterface
    {
        $this->query->addAggregation((new StringFacetAggregation())->getAggregation());

        return $this;
    }

    /**
     * @return QueryBuilder
     */
    public function createNewQueryBuilder(): QueryBuilder
    {
        return new static($this->filterBuilder, $this->sortBuilder);
    }
}
