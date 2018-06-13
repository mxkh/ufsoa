<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Filter\Factory;

use UmberFirm\Component\Catalog\Filter\NestedQueryInterface;
use UmberFirm\Component\Catalog\Filter\QueryStringNestedQueryInterface;

/**
 * Interface FilterFactoryInterface
 *
 * @package UmberFirm\Component\Catalog
 */
interface FilterFactoryInterface
{
    /**
     * @param string $type
     *
     * @return null|NestedQueryInterface
     */
    public function createFacetFilter(string $type): ?NestedQueryInterface;

    /**
     * @return QueryStringNestedQueryInterface
     */
    public function createQueryStringFilter(): QueryStringNestedQueryInterface;
}
