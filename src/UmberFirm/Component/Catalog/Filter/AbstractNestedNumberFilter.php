<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Filter;

/**
 * Class AbstractNestedNumberFilter
 *
 * @package UmberFirm\Component\Catalog\Filter
 */
abstract class AbstractNestedNumberFilter extends AbstractNestedQuery
{
    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        return (string) self::FACET_NUMBER;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return (string) self::FILTER_RANGE;
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
