<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Filter;

use Doctrine\Common\Collections\Collection;
use Elastica\Filter\AbstractFilter;
use Elastica\Query\AbstractQuery;

/**
 * Interface FilterBuilderInterface
 *
 * @package UmberFirm\Component\Catalog\Filter
 */
interface FilterBuilderInterface
{
    /**
     * @param array $term
     *
     * @return AbstractQuery
     */
    public function buildTermQuery(array $term): AbstractQuery;

    /**
     * @param array $term
     *
     * @return AbstractQuery
     */
    public function buildTermsQuery(array $term = []): AbstractQuery;

    /**
     * @param array $term
     *
     * @return AbstractFilter
     */
    public function buildTermFilter(array $term): AbstractFilter;

    /**
     * @param array $categories
     *
     * @return AbstractQuery
     */
    public function buildCategoryQuery(array $categories): AbstractQuery;

    /**
     * @param array $categories
     *
     * @return AbstractFilter
     */
    public function buildCategoryFilter(array $categories): AbstractFilter;

    /**
     * @param Collection $attributeCollection
     *
     * @return \Generator
     */
    public function buildFacetFilterCollection(Collection $attributeCollection): \Generator;
}
