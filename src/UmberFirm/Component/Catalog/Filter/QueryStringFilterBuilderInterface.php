<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Filter;

/**
 * Class QueryStringFilterBuilderInterface
 *
 * @package UmberFirm\Component\Catalog\Filter
 */
interface QueryStringFilterBuilderInterface
{
    /**
     * @param string $phrase
     *
     * @return QueryStringNestedQueryInterface
     */
    public function buildQueryStringFilter(string $phrase): QueryStringNestedQueryInterface;
}
