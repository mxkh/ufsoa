<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Model\Elastica;

/**
 * Class Product
 *
 * @package UmberFirm\Bundle\ProductBundle\Model\Elastica
 */
class ProductModel
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $article;

    /**
     * @var bool
     */
    private $isOutOfStock;

    /**
     * @var bool
     */
    private $isActive;

    /**
     * @var bool
     */
    private $isNew;

    /**
     * @var bool
     */
    private $isPreOrder;

    /**
     * @var bool
     */
    private $isSale;

    /**
     * @var bool
     */
    private $isHidden;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $shortDescription;

    /**
     * @var array
     */
    private $variants;

    /**
     * @var array
     */
    private $manufacturer;

    /**
     * @var float
     */
    private $price;

    /**
     * @var float
     */
    private $salePrice;

    /**
     * @var array
     */
    private $medias;

    /**
     * @var array
     */
    private $categories;

    /**
     * @var array
     */
    private $createdAt;

    /**
     * @var array
     */
    private $updatedAt;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param array $manufacturer
     *
     * @return ProductModel
     */
    public function setManufacturer(array $manufacturer): ProductModel
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    /**
     * @return array
     */
    public function getManufacturer(): array
    {
        return $this->manufacturer;
    }

    /**
     * @param float $price
     *
     * @return ProductModel
     */
    public function setPrice(float $price): ProductModel
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $salePrice
     *
     * @return ProductModel
     */
    public function setSalePrice(float $salePrice): ProductModel
    {
        $this->salePrice = $salePrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getSalePrice(): float
    {
        return $this->salePrice;
    }

    /**
     * @param string $name
     *
     * @return ProductModel
     */
    public function setName(string $name): ProductModel
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param array $medias
     *
     * @return ProductModel
     */
    public function setMedias(array $medias): ProductModel
    {
        $this->medias = $medias;

        return $this;
    }

    /**
     * @return array
     */
    public function getMedias(): array
    {
        return $this->medias;
    }

    /**
     * @param string $description
     *
     * @return ProductModel
     */
    public function setDescription(string $description): ProductModel
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $shortDescription
     *
     * @return ProductModel
     */
    public function setShortDescription(string $shortDescription): ProductModel
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * @return string
     */
    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }

    /**
     * @param array $variants
     *
     * @return ProductModel
     */
    public function setVariants(array $variants): ProductModel
    {
        $this->variants = $variants;

        return $this;
    }

    /**
     * @return array
     */
    public function getVariants(): array
    {
        return $this->variants;
    }

    /**
     * @param string $slug
     *
     * @return ProductModel
     */
    public function setSlug(string $slug): ProductModel
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return (string) $this->slug;
    }

    /**
     * @param bool $isOutOfStock
     *
     * @return ProductModel
     */
    public function setIsOutOfStock(bool $isOutOfStock): ProductModel
    {
        $this->isOutOfStock = $isOutOfStock;

        return $this;
    }

    /**
     * @return bool
     */
    public function isOutOfStock(): bool
    {
        return (bool) $this->isOutOfStock;
    }

    /**
     * @param string $article
     *
     * @return ProductModel
     */
    public function setArticle(string $article): ProductModel
    {
        $this->article = $article;

        return $this;
    }

    /**
     * @return string
     */
    public function getArticle(): string
    {
        return $this->article;
    }

    /**
     * @param bool $isActive
     *
     * @return ProductModel
     */
    public function setIsActive(bool $isActive): ProductModel
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool) $this->isActive;
    }

    /**
     * @param bool $isNew
     *
     * @return ProductModel
     */
    public function setIsNew(bool $isNew): ProductModel
    {
        $this->isNew = $isNew;

        return $this;
    }

    /**
     * @return bool
     */
    public function isNew(): bool
    {
        return (bool) $this->isNew;
    }

    /**
     * @param bool $isPreOrder
     *
     * @return ProductModel
     */
    public function setIsPreOrder(bool $isPreOrder): ProductModel
    {
        $this->isPreOrder = $isPreOrder;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPreOrder(): bool
    {
        return (bool) $this->isPreOrder;
    }

    /**
     * @param bool $isSale
     *
     * @return ProductModel
     */
    public function setIsSale(bool $isSale): ProductModel
    {
        $this->isSale = $isSale;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSale(): bool
    {
        return (bool) $this->isSale;
    }

    /**
     * @param array $categories
     *
     * @return ProductModel
     */
    public function setCategories(array $categories): ProductModel
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @param bool $isHidden
     *
     * @return ProductModel
     */
    public function setIsHidden(bool $isHidden): ProductModel
    {
        $this->isHidden = $isHidden;

        return $this;
    }

    /**
     * @return bool
     */
    public function isHidden(): bool
    {
        return $this->isHidden;
    }

    /**
     * @param array $createdAt
     *
     * @return ProductModel
     */
    public function setCreatedAt(array $createdAt): ProductModel
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @param array $updatedAt
     *
     * @return ProductModel
     */
    public function setUpdatedAt(array $updatedAt): ProductModel
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return array
     */
    public function getCreatedAt(): array
    {
        return $this->createdAt;
    }

    /**
     * @return array
     */
    public function getUpdatedAt(): array
    {
        return $this->updatedAt;
    }
}
