<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Transformer;

use UmberFirm\Bundle\ProductBundle\Entity\Product;

/**
 * Class ProductToElasticaTransformerInterface
 *
 * @package UmberFirm\Bundle\ProductBundle\Transformer
 */
interface ProductToElasticaTransformerInterface
{
    /**
     * @param Product $product
     *
     * @return array
     */
    public function getSearchData(Product $product): array;

    /**
     * @param Product $product
     *
     * @return array
     */
    public function getCategories(Product $product): array;

    /**
     * @return array
     */
    public function getFullTextValues(): array;

    /**
     * @param array $fullTextValues
     *
     * @return ProductToElasticaTransformerInterface
     */
    public function setFullTextValues(array $fullTextValues): ProductToElasticaTransformerInterface;

    /**
     * @param mixed $value
     *
     * @return ProductToElasticaTransformerInterface
     */
    public function addFullTextValue($value): ProductToElasticaTransformerInterface;

    /**
     * @return void
     */
    public function resetFullTextValues(): void;

    /**
     * @param array $fullTextValues
     *
     * @return string
     */
    public function normalizeFullTextValues(array $fullTextValues): string;

    /**
     * @param Product $product
     *
     * @return array
     */
    public function getProductMedias(Product $product): array;
}
