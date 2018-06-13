<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Filter;

/**
 * Class AbstractNestedQueryStringFilter
 *
 * @package UmberFirm\Component\Catalog\Filter
 */
abstract class AbstractNestedQueryStringFilter extends AbstractNestedQuery
{
    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        return (string) self::SEARCH_FIELD_NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return (string) self::FILTER_STRING;
    }

    /**
     * {@inheritdoc}
     */
    public function setType(string $type): NestedQueryInterface
    {
        // TODO: Implement setType() method.

        return $this;
    }
}
