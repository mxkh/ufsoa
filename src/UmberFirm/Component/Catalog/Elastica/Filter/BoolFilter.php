<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Elastica\Filter;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Elastica\Filter\AbstractFilter;
use Elastica\Filter\BoolFilter as ElasticaBoolFilter;
use Elastica\Query\AbstractQuery;

/**
 * Class BoolFilter
 *
 * @package UmberFirm\Component\Catalog\Elastica\Filter
 */
class BoolFilter extends ElasticaBoolFilter
{
    /**
     * @param string $filterClass
     * @param string $filterField
     *
     * @return BoolFilter
     */
    public function removeFilterFromMust(string $filterClass, string $filterField): BoolFilter
    {
        foreach ($this->_must as $key => $object) {
            if (false === $this->isEquals($filterClass, $object)) {
                continue;
            }

            $query = $this->getFilterQuery($object);
            $must = $this->getQueryMustCollection($query);
            $facetField = $this->getQueryTermFieldValue($must->first(), 'search_data.string_facets.key');

            if (true === empty($facetField)) {
                continue;
            }

            if ($facetField === $filterField) {
                unset($this->_must[$key]);
                $this->_must = array_values($this->_must);
            }
        }

        return $this;
    }

    /**
     * @param string $expected
     * @param AbstractFilter $actual
     *
     * @return bool
     */
    protected function isEquals(string $expected, AbstractFilter $actual): bool
    {
        return $expected === get_class($actual);
    }

    /**
     * @param AbstractFilter $abstractFilter
     *
     * @return AbstractQuery
     */
    protected function getFilterQuery(AbstractFilter $abstractFilter): AbstractQuery
    {
        return $abstractFilter->getParam('query');
    }

    /**
     * @param AbstractQuery $abstractQuery
     *
     * @return Collection
     */
    protected function getQueryMustCollection(AbstractQuery $abstractQuery): Collection
    {
        return new ArrayCollection($abstractQuery->getParam('must'));
    }

    /**
     * @param AbstractQuery $term
     * @param string $fieldName
     *
     * @return string
     */
    protected function getQueryTermFieldValue(AbstractQuery $term, string $fieldName): string
    {
        if (false === $term->hasParam($fieldName)) {
            return '';
        }

        return $term->getParam($fieldName);
    }
}
