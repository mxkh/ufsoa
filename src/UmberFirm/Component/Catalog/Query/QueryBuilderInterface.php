<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Query;

use Doctrine\Common\Collections\Collection;
use Elastica\Filter\AbstractFilter;
use Elastica\Query;
use Elastica\Query\AbstractQuery;
use UmberFirm\Component\Catalog\Elastica\Filter\BoolFilter;
use UmberFirm\Component\Catalog\Filter\NestedFilterInterface;
use UmberFirm\Component\Catalog\Filter\NestedQueryInterface;

/**
 * Interface QueryBuilderInterface
 *
 * @package UmberFirm\Component\Catalog\Query
 */
interface QueryBuilderInterface
{
    /**
     * @param Collection $collection
     *
     * @return QueryBuilderInterface
     */
    public function buildQueryContext(Collection $collection): QueryBuilderInterface;

    /**
     * @param Collection $collection
     *
     * @return QueryBuilderInterface
     */
    public function buildFilterContext(Collection $collection): QueryBuilderInterface;

    /**
     * @param Collection $collection
     *
     * @return QueryBuilderInterface
     */
    public function buildSortQuery(Collection $collection): QueryBuilderInterface;

    /**
     * @param string $phrase
     *
     * @return QueryBuilderInterface
     */
    public function buildQueryStringQuery(string $phrase): QueryBuilderInterface;

    /**
     * @param NestedQueryInterface $filter
     *
     * @return AbstractQuery
     */
    public function getFilterQueryContext(NestedQueryInterface $filter): AbstractQuery;

    /**
     * @param NestedFilterInterface $filter
     *
     * @return AbstractFilter
     */
    public function getFilterContext(NestedFilterInterface $filter): AbstractFilter;

    /**
     * @param SortInterface $sort
     *
     * @return array
     */
    public function getSortQuery(SortInterface $sort): array;

    /**
     * @return Query
     */
    public function getQuery(): Query;

    /**
     * @return BoolFilter
     */
    public function getFilter(): BoolFilter;

    /**
     * @param array $term
     *
     * @return QueryBuilderInterface
     */
    public function addTermsQuery(array $term): QueryBuilderInterface;

    /**
     * @param array $term
     *
     * @return QueryBuilderInterface
     */
    public function addTermFilter(array $term): QueryBuilderInterface;

    /**
     * @param array $categories
     *
     * @return QueryBuilderInterface
     */
    public function buildCategoryQuery(array $categories): QueryBuilderInterface;

    /**
     * @param array $categories
     *
     * @return QueryBuilderInterface
     */
    public function buildCategoryFilter(array $categories): QueryBuilderInterface;

    /**
     * @return QueryBuilderInterface
     */
    public function buildDefaultAggregationalQuery(): QueryBuilderInterface;
}
