<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Filter\Factory;

use UmberFirm\Component\Catalog\Filter\CheckboxFilter;
use UmberFirm\Component\Catalog\Filter\NestedQueryInterface;
use UmberFirm\Component\Catalog\Filter\QueryStringFilter;
use UmberFirm\Component\Catalog\Filter\QueryStringNestedQueryInterface;
use UmberFirm\Component\Catalog\Filter\RangeFilter;

/**
 * Class FilterFactory
 *
 * @package UmberFirm\Component\Catalog
 */
final class FilterFactory implements FilterFactoryInterface
{
    /**
     * @var NestedQueryInterface[]
     */
    private $facetFilters = [];

    /**
     * FilterFactory constructor.
     */
    public function __construct()
    {
        $this->facetFilters = [
            'checkbox' => new CheckboxFilter(),
            'range' => new RangeFilter(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function createFacetFilter(string $type): ?NestedQueryInterface
    {
        if (false === array_key_exists($type, $this->facetFilters)) {
            return null;
        }

        return clone $this->facetFilters[$type];
    }

    /**
     * {@inheritdoc}
     */
    public function createQueryStringFilter(): QueryStringNestedQueryInterface
    {
        return new QueryStringFilter();
    }
}
