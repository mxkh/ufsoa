<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Query;

use Elastica\Filter\AbstractFilter;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\Nested as QueryNested;
use Elastica\Filter\Nested as FilterNested;
use Elastica\Query\Term;
use Elastica\Query\Terms;
use UmberFirm\Component\Catalog\Filter\NestedQueryInterface;

/**
 * Class StringFacetQuery
 *
 * @package UmberFirm\Component\Catalog\Query
 */
final class StringFacetQuery implements QueryInterface, FilterInterface
{
    /**
     * @var NestedQueryInterface
     */
    private $filter;

    /**
     * StringFacetQuery constructor.
     *
     * @param NestedQueryInterface $filter
     */
    public function __construct(NestedQueryInterface $filter)
    {
        $this->filter = $filter;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery(): AbstractQuery
    {
        $nested = new QueryNested();
        $nested->setPath($this->filter->getPath());
        $nestedBool = new BoolQuery();
        $nestedBool->addMust(new Term([$this->filter->getKeyFieldName() => $this->filter->getName()]));
        $nestedBool->addMust(new Terms($this->filter->getValueFieldName(), $this->filter->getValue()));
        $nested->setQuery($nestedBool);

        return $nested;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilter(): AbstractFilter
    {
        $nested = new FilterNested();
        $nested->setPath($this->filter->getPath());
        $nestedBool = new BoolQuery();
        $nestedBool->addMust(new Term([$this->filter->getKeyFieldName() => $this->filter->getName()]));
        $nestedBool->addMust(new Terms($this->filter->getValueFieldName(), $this->filter->getValue()));
        $nested->setQuery($nestedBool);

        return $nested;
    }
}
