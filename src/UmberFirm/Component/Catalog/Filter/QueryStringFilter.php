<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Filter;

use Elastica\Query\AbstractQuery;
use UmberFirm\Component\Catalog\Query\QueryStringQuery;

/**
 * Class QueryStringFilter
 *
 * @package UmberFirm\Component\Catalog\Filter
 */
final class QueryStringFilter extends AbstractNestedQueryStringFilter implements QueryStringNestedQueryInterface
{
    /**
     * @var string
     */
    private $defaultField;

    /**
     * @var string
     */
    private $defaultOperator = self::OPERATOR_AND;

    /**
     * @var string
     */
    private $phrase;

    /**
     * {@inheritdoc}
     */
    public function getQuery(): AbstractQuery
    {
        if (null === $this->query) {
            $this->query = new QueryStringQuery($this);
        }

        return $this->query->getQuery();
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultField(): string
    {
        return (string) $this->defaultField;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultField(string $defaultField): QueryStringNestedQueryInterface
    {
        $this->defaultField = sprintf($this->nestedFieldFormat, $this->getPath(), $defaultField);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOperator(): string
    {
        return (string) $this->defaultOperator;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOperator(string $defaultOperator): QueryStringNestedQueryInterface
    {
        $this->defaultOperator = $defaultOperator;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPhrase(): string
    {
        return (string) $this->phrase;
    }

    /**
     * {@inheritdoc}
     */
    public function setPhrase(string $phrase): QueryStringNestedQueryInterface
    {
        $this->phrase = $phrase;

        return $this;
    }
}
