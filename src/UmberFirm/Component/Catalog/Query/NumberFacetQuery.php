<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Query;

use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\Nested;
use Elastica\Query\Range;
use Elastica\Query\Term;
use UmberFirm\Component\Catalog\Filter\RangeQueryInterface;

/**
 * Class NumberFacetQuery
 *
 * @package UmberFirm\Component\Catalog\Query
 */
final class NumberFacetQuery implements QueryInterface
{
    /**
     * @var RangeQueryInterface
     */
    private $filter;

    /**
     * StringFacetQuery constructor.
     *
     * @param RangeQueryInterface $filter
     */
    public function __construct(RangeQueryInterface $filter)
    {
        $this->filter = $filter;
    }

    /**
     * @return AbstractQuery
     */
    public function getQuery(): AbstractQuery
    {
        $params = ['gte' => $this->filter->getMin()];

        if ($this->filter->getMax() > $this->filter->getMin()) {
            $params['lte'] = $this->filter->getMax();
        }

        $nested = new Nested();
        $nested->setPath($this->filter->getPath());
        $nestedBool = new BoolQuery();
        $nestedBool->addMust(new Term([$this->filter->getKeyFieldName() => $this->filter->getName()]));
        $nestedBool->addMust(
            new Range(
                $this->filter->getValueFieldName(),
                $params
            )
        );
        $nested->setQuery($nestedBool);

        return $nested;
    }
}
