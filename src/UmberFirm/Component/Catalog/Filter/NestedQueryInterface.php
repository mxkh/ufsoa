<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Filter;

use Elastica\Query\AbstractQuery;

/**
 * Interface NestedQueryInterface
 *
 * @package UmberFirm\Component\Catalog
 */
interface NestedQueryInterface
{
    const FILTER_STRING = 'string';
    const FILTER_RANGE = 'range';

    const SEARCH_FIELD_NAME = 'search_data';

    const FACET_STRING = 'search_data.string_facets';
    const FACET_NUMBER = 'search_data.number_facets';

    const KEY_FIELD_NAME = 'key';
    const VALUE_FIELD_NAME = 'value';

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param string $type
     *
     * @return NestedQueryInterface
     */
    public function setType(string $type): NestedQueryInterface;

    /**
     * @return string
     */
    public function getPath(): string;

    /**
     * @param string $path
     *
     * @return NestedQueryInterface
     */
    public function setPath(string $path): NestedQueryInterface;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     *
     * @return NestedQueryInterface
     */
    public function setName(string $name): NestedQueryInterface;

    /**
     * @return array
     */
    public function getValue(): array;

    /**
     * @param array $value
     *
     * @return NestedQueryInterface
     */
    public function setValue(array $value): NestedQueryInterface;

    /**
     * @return string
     */
    public function getKeyFieldName(): string;

    /**
     * @param string $name
     *
     * @return NestedQueryInterface
     */
    public function setKeyFieldName(string $name): NestedQueryInterface;

    /**
     * @return string
     */
    public function getValueFieldName(): string;

    /**
     * @param string $name
     *
     * @return NestedQueryInterface
     */
    public function setValueFieldName(string $name): NestedQueryInterface;

    /**
     * @return AbstractQuery
     */
    public function getQuery(): AbstractQuery;
}
