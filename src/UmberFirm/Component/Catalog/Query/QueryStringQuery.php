<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Query;

use Elastica\Query\AbstractQuery;
use Elastica\Query\Nested;
use Elastica\Query\QueryString;
use UmberFirm\Component\Catalog\Filter\QueryStringNestedQueryInterface;

/**
 * Class QueryStringQuery
 *
 * @package UmberFirm\Component\Catalog\Query
 */
final class QueryStringQuery implements QueryInterface
{
    /**
     * @var QueryStringNestedQueryInterface
     */
    private $filter;

    /**
     * QueryStringQuery constructor.
     *
     * @param QueryStringNestedQueryInterface $filter
     */
    public function __construct(QueryStringNestedQueryInterface $filter)
    {
        $this->filter = $filter;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery(): AbstractQuery
    {
        $nested = new Nested();
        $nested->setPath($this->filter->getPath());
        $query = new QueryString();
        $query->setDefaultField($this->filter->getDefaultField())
            ->setDefaultOperator($this->filter->getDefaultOperator())
            ->setQuery($this->filter->getPhrase());
        $nested->setQuery($query);

        return $nested;
    }
}
