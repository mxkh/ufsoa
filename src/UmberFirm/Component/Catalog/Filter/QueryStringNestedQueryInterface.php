<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Filter;

/**
 * Interface QueryStringNestedQueryInterface
 *
 * @package UmberFirm\Component\Catalog\Filter
 */
interface QueryStringNestedQueryInterface extends NestedQueryInterface
{
    const DEFAULT_FIELD = 'full_text';

    const OPERATOR_AND = 'AND';
    const OPERATOR_OR = 'OR';

    /**
     * @return string
     */
    public function getDefaultField(): string;

    /**
     * @param string $defaultField
     *
     * @return QueryStringNestedQueryInterface
     */
    public function setDefaultField(string $defaultField): QueryStringNestedQueryInterface;

    /**
     * @return string
     */
    public function getDefaultOperator(): string;

    /**
     * @param string $defaultOperator
     *
     * @return QueryStringNestedQueryInterface
     */
    public function setDefaultOperator(string $defaultOperator): QueryStringNestedQueryInterface;

    /**
     * @return string
     */
    public function getPhrase(): string;

    /**
     * @param string $phrase
     *
     * @return QueryStringNestedQueryInterface
     */
    public function setPhrase(string $phrase): QueryStringNestedQueryInterface;
}
