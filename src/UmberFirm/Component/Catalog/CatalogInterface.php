<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog;

use UmberFirm\Bundle\ProductBundle\Model\Elastica\ProductModel;

/**
 * Interface CatalogInterface
 *
 * @package UmberFirm\Component\Catalog
 */
interface CatalogInterface
{
    /**
     * @param string $slug
     * @param bool|null $isActive
     * @param bool|null $isOutOfStock
     *
     * @return null|ProductModel
     */
    public function findOne(string $slug, ?bool $isActive, ?bool $isOutOfStock): ?ProductModel;

    /**
     * @param array $attributes Default empty array
     * @param array $sort Default empty array
     * @param string $phrase Default empty string
     *
     * @return array
     */
    public function find(array $attributes = [], array $sort = [], string $phrase = ''): array;

    /**
     * @param array $categories
     * @param array $attributes
     * @param array $sort
     * @param array $terms
     * @param string $phrase
     *
     * @return array
     */
    public function getAggregations(
        array $categories = [],
        array $attributes = [],
        array $sort = [],
        array $terms = [],
        string $phrase = ''
    ): array;
}
