<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Transformer;

use Elastica\Document;
use FOS\ElasticaBundle\Transformer\ModelToElasticaAutoTransformer;
use FOS\ElasticaBundle\Transformer\ModelToElasticaTransformerInterface;
use UmberFirm\Bundle\CategoryBundle\Entity\Category;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\MediaBundle\Entity\Media;
use UmberFirm\Bundle\ProductBundle\Entity\Attribute;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductMedia;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariantMedia;

/**
 * Class ProductVariantToElasticaTransformer
 *
 * @package UmberFirm\Bundle\ProductBundle\Transformer
 */
final class ProductToElasticaTransformer extends ModelToElasticaAutoTransformer implements
    ModelToElasticaTransformerInterface,
    ProductToElasticaTransformerInterface
{
    /**
     * @var array
     */
    private $fullTextValues = [];

    /**
     * @param Product $product
     *
     * {@inheritdoc}
     */
    public function transform($product, array $fields)
    {
        $identifier = $product->getId()->toString();
        $values = [
            'id' => $identifier,
            'name' => $product->getName(),
            'slug' => $product->getSlug(),
            'article' => $product->getArticle(),
            'url' => '',
            'is_hidden' => $product->isHidden(),
            'is_out_of_stock' => $product->isOutOfStock(),
            'is_new' => $product->isNew(),
            'is_pre_order' => $product->isPreOrder(),
            'is_active' => $this->getIsActive($product),
            'is_sale' => (bool) ($product->getSalePrice() < $product->getPrice()),
            'description' => $product->getDescription(),
            'short_description' => $product->getShortDescription(),
            'manufacturer' => $this->createManufacturer($product->getManufacturer()),
            'price' => $product->getPrice(),
            'sale_price' => $product->getSalePrice(),
            'created_at' => $product->getCreatedAt(),
            'updated_at' => $product->getUpdatedAt(),
            'medias' => $this->getProductMedias($product),
            'categories' => $this->getCategories($product),
            'search_data' => $this->getSearchData($product),
        ];

        $document = new Document($identifier, $values);

        return $document;
    }

    /**
     * {@inheritdoc}
     */
    public function getSearchData(Product $product): array
    {
        $variants = [];

        /** @var ProductVariant $productVariant */
        foreach ($product->getProductVariants() as $productVariant) {
            // Add product variant data for full_text field
            $this
                ->addFullTextValue($productVariant->getSalePrice())
                ->addFullTextValue($product->getArticle());

            if (null !== $product->getManufacturer()) {
                $this->addFullTextValue($product->getManufacturer()->getName());
            }

            $facets = [
                'id' => $productVariant->getId()->toString(),
                'price' => $productVariant->getPrice(),
                'sale_price' => $productVariant->getSalePrice(),
                'is_out_of_stock' => $productVariant->isOutOfStock(),
                'url' => '',
                'medias' => $this->getProductVariantMedias($productVariant),
                'attributes' => $this->getProductVariantAttributes($productVariant),
                'string_facets' => array_map(
                    function (Attribute $attribute): array {
                        // Add facet value for full_text field
                        $this->addFullTextValue($attribute->getName());

                        return [
                            'key' => $attribute->getAttributeGroup()->getCode(),
                            'value' => $attribute->getSlug(),
                        ];
                    },
                    $productVariant->getProductVariantAttributes()->toArray()
                ),
                'number_facets' => [
                    [
                        'key' => 'sale_price',
                        'value' => $productVariant->getSalePrice(),
                    ],
                ],
            ];

            if (null !== $product->getManufacturer()) {
                // Add manufacturer to the facets mapping
                $facets['string_facets'][] = [
                    'key' => 'manufacturer',
                    'value' => $product->getManufacturer()->getSlug(),
                ];
            }

            $facets['full_text'] = $this->normalizeFullTextValues($this->getFullTextValues());

            $variants[] = $facets;
        }

        return $variants;
    }

    /**
     * {@inheritdoc}
     */
    public function getCategories(Product $product): array
    {
        $categories = [];

        /** @var Category $category */
        foreach ($product->getCategories() as $category) {
            $categories[] = [
                'id' => $category->getId()->toString(),
                'title' => $category->getTitle(),
                'slug' => $category->getSlug(),
            ];

            // Add category name for full_text field
            $this->addFullTextValue($category->getTitle());

            $parent = $category;
            for ($i = $category->getLevel(); $i > 0; --$i) {
                $parent = $parent->getParent();
                $categories[] = [
                    'id' => $parent->getId()->toString(),
                    'title' => $parent->getTitle(),
                    'slug' => $parent->getSlug(),
                ];

                // Add category name for full_text field
                $this->addFullTextValue($parent->getTitle());
            }
        }

        return $categories;
    }

    /**
     * {@inheritdoc}
     */
    public function getFullTextValues(): array
    {
        return $this->fullTextValues;
    }

    /**
     * {@inheritdoc}
     */
    public function setFullTextValues(array $fullTextValues): ProductToElasticaTransformerInterface
    {
        $this->fullTextValues = $fullTextValues;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addFullTextValue($value): ProductToElasticaTransformerInterface
    {
        if (false === empty($value)) {
            $this->fullTextValues[] = $value;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function resetFullTextValues(): void
    {
        $this->setFullTextValues([]);
    }

    /**
     * {@inheritdoc}
     */
    public function normalizeFullTextValues(array $fullTextValues): string
    {
        $this->resetFullTextValues();

        return implode(', ', $fullTextValues);
    }

    /**
     * {@inheritdoc}
     */
    public function getProductMedias(Product $product): array
    {
        $medias = array_map(
            function (ProductMedia $productMedia) {
                $media = $this->createMedia($productMedia->getMedia());

                return $this->addPositionToMedia($media, $productMedia->getPosition());
            },
            $product->getMedias()->toArray()
        );

        return $this->sortMedia($medias);
    }

    /**
     * @param ProductVariant $productVariant
     *
     * @return array
     */
    private function getProductVariantMedias(ProductVariant $productVariant): array
    {
        $medias = array_map(
            function (ProductVariantMedia $productVariantMedia) {
                $media = $this->createMedia($productVariantMedia->getProductMedia()->getMedia());

                return $this->addPositionToMedia($media, $productVariantMedia->getPosition());
            },
            $productVariant->getMedias()->toArray()
        );

        return $this->sortMedia($medias);
    }

    /**
     * Helper method
     * Returns array of metadata of media
     *
     * @param Media $media
     *
     * @return array
     */
    private function createMedia(Media $media): array
    {
        return [
            'filename' => $media->getFilename(),
            'extension' => $media->getExtension(),
            'mimeType' => $media->getMimeType(),
        ];
    }

    /**
     * Helper method
     * Returns array of metadata of media
     *
     * @param ProductVariant $variant
     *
     * @return array
     */
    private function getProductVariantAttributes(ProductVariant $variant): array
    {
        $attributes = array_reduce(
            $variant->getProductVariantAttributes()->toArray(),
            function (array $result, Attribute $attribute) {
                $attributeMap = $this->getProductVariantAttributesMap($attribute);
                $result[key($attributeMap)] = $attributeMap[key($attributeMap)];

                return $result;
            },
            []
        );

        return $attributes;
    }

    /**
     * Helper method.
     *
     * @param Attribute $attribute
     *
     * @return array
     */
    private function getProductVariantAttributesMap(Attribute $attribute): array
    {
        return [
            $attribute->getAttributeGroup()->getCode() => [
                'name' => $attribute->getAttributeGroup()->getName(),
                'value' => $attribute->getName(),
                'position' => $attribute->getPosition(),
            ],
        ];
    }

    /**
     * @param null|Manufacturer $manufacturer
     *
     * @return array
     */
    private function createManufacturer(?Manufacturer $manufacturer): array
    {
        if (null === $manufacturer) {
            return [];
        }

        return [
            'id' => $manufacturer->getId()->toString(),
            'name' => $manufacturer->getName(),
            'slug' => $manufacturer->getSlug(),
        ];
    }

    /**
     * Helper method.
     *
     * @param Product $product
     *
     * @return bool
     */
    private function getIsActive(Product $product): bool
    {
        $isActive = true;
        if (
            true === $product->isHidden() ||
            true === (0 === $product->getCategories()->count()) ||
            true === (0 === $product->getMedias()->count()) ||
            true === (null === $product->getManufacturer())
        ) {
            $isActive = false;
        }

        return $isActive;
    }

    /**
     * Helper method.
     *
     * @param array $medias
     *
     * @return array
     */
    private function sortMedia(array $medias): array
    {
        $position = array_map(
            function (array $media) {
                return $media['position'];
            },
            $medias
        );

        array_multisort($position, SORT_ASC, $medias);

        return $medias;
    }

    /**
     * Helper method.
     *
     * @param array $media
     * @param int $position
     *
     * @return array
     */
    private function addPositionToMedia(array $media, int $position): array
    {
        $media['position'] = $position;

        return $media;
    }
}
