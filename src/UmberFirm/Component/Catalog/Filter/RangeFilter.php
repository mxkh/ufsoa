<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Filter;

use Elastica\Query\AbstractQuery;
use UmberFirm\Component\Catalog\Query\NumberFacetQuery;

/**
 * Class RangeFilter
 *
 * @package UmberFirm\Component\Catalog\Filter
 */
final class RangeFilter extends AbstractNestedNumberFilter implements RangeQueryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getQuery(): AbstractQuery
    {
        if (null === $this->query) {
            $this->query = new NumberFacetQuery($this);
        }

        return $this->query->getQuery();
    }

    /**
     * {@inheritdoc}
     */
    public function getMin(): int
    {
        if (false === isset($this->getValue()['min'])) {
            return 0;
        }

        return (int) $this->getValue()['min'];
    }

    /**
     * {@inheritdoc}
     */
    public function getMax(): int
    {
        if (false === isset($this->getValue()['max'])) {
            return 0;
        }

        return (int) $this->getValue()['max'];
    }
}
