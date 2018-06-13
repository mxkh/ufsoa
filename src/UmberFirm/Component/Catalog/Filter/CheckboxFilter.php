<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Filter;

use Elastica\Filter\AbstractFilter;
use Elastica\Query\AbstractQuery;
use UmberFirm\Component\Catalog\Query\StringFacetQuery;

/**
 * Class CheckboxFilter
 *
 * @package UmberFirm\Component\Catalog
 */
final class CheckboxFilter extends AbstractNestedStringFilter implements NestedFilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function getQuery(): AbstractQuery
    {
        if (null === $this->query) {
            $this->query = new StringFacetQuery($this);
        }

        return $this->query->getQuery();
    }

    /**
     * @return AbstractFilter
     */
    public function getFilter(): AbstractFilter
    {
        if (null === $this->query) {
            $this->query = new StringFacetQuery($this);
        }

        return $this->query->getFilter();
    }
}
