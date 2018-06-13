<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Filter;

/**
 * Class AbstractNestedStringFilter
 *
 * @package UmberFirm\Component\Catalog
 */
abstract class AbstractNestedStringFilter extends AbstractNestedQuery
{
    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        return (string) self::FACET_STRING;
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
