<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class ProductVariant
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ProductBundle\Repository\ProductVariantRepository")
 */
class ProductVariant implements
    UuidEntityInterface,
    ProductVariantAttributeAwareInterface,
    ProductVariantMediaAwareInterface
{
    use UuidTrait;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(
     *     targetEntity="UmberFirm\Bundle\ProductBundle\Entity\Product",
     *     inversedBy="productVariants"
     * )
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $product;

    /**
     * @var Collection|Attribute[]
     *
     * @ORM\ManyToMany(targetEntity="UmberFirm\Bundle\ProductBundle\Entity\Attribute")
     */
    private $productVariantAttributes;

    /**
     * @var ProductVariantMedia[]|Collection
     *
     * @ORM\OneToMany(
     *     targetEntity="UmberFirm\Bundle\ProductBundle\Entity\ProductVariantMedia",
     *     mappedBy="productVariant"
     * )
     */
    private $medias;

    /**
     * @var Shop
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\Shop")
     */
    private $shop;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="UmberFirm\Bundle\ProductBundle\Entity\Department",
     *     mappedBy="productVariant"
     * )
     */
    private $departments;

    /**
     * @var float
     *
     * @ORM\Column(type="float", precision=6, length=20, nullable=true, options={"default": "0.000000"})
     */
    private $price;

    /**
     * @var float
     *
     * @ORM\Column(type="float", precision=6, length=20, nullable=true, options={"default": "0.000000"})
     */
    private $salePrice;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    private $article;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false, options={"default":false})
     */
    private $outOfStock = false;

    /**
     * ProductAttribute constructor.
     */
    public function __construct()
    {
        $this->productVariantAttributes = new ArrayCollection();
        $this->medias = new ArrayCollection();
        $this->departments = new ArrayCollection();
    }

    /**
     * @return Product|null
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param null|Product $product
     *
     * @return ProductVariant
     */
    public function setProduct(?Product $product): ProductVariant
    {
        $this->product = $product;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getProductVariantAttributes(): Collection
    {
        return $this->productVariantAttributes;
    }

    /**
     * {@inheritdoc}
     */
    public function addProductVariantAttribute(Attribute $productVariantAttribute): ProductVariant
    {
        if (false === $this->productVariantAttributes->contains($productVariantAttribute)) {
            $this->productVariantAttributes->add($productVariantAttribute);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeProductVariantAttribute(Attribute $productVariantAttribute): ProductVariant
    {
        if (true === $this->productVariantAttributes->contains($productVariantAttribute)) {
            $this->productVariantAttributes->removeElement($productVariantAttribute);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMedias(): Collection
    {
        return $this->medias;
    }

    /**
     * {@inheritdoc}
     */
    public function addMedia(ProductVariantMedia $media): ProductVariant
    {
        if (false === $this->medias->contains($media)) {
            $this->medias->add($media);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeMedia(ProductVariantMedia $media): ProductVariant
    {
        if (true === $this->medias->contains($media)) {
            $this->medias->removeElement($media);
        }

        return $this;
    }

    /**
     * @return null|Shop
     */
    public function getShop(): ?Shop
    {
        return $this->shop;
    }

    /**
     * @param null|Shop $shop
     *
     * @return ProductVariant
     */
    public function setShop(?Shop $shop): ProductVariant
    {
        $this->shop = $shop;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getDepartments(): Collection
    {
        return $this->departments;
    }

    /**
     * @param Department $department
     *
     * @return ProductVariant
     */
    public function addDepartment(Department $department): ProductVariant
    {
        if (false === $this->departments->contains($department)) {
            $department->setProductVariant($this);
            $this->departments->add($department);
        }

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return (float) $this->price;
    }

    /**
     * @param null|float $price
     *
     * @return ProductVariant
     */
    public function setPrice(?float $price): ProductVariant
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return float
     */
    public function getSalePrice(): float
    {
        return (float) $this->salePrice;
    }

    /**
     * @param null|float $salePrice
     *
     * @return ProductVariant
     */
    public function setSalePrice(?float $salePrice): ProductVariant
    {
        $this->salePrice = $salePrice;

        return $this;
    }

    /**
     * @param bool|null $outOfStock
     *
     * @return ProductVariant
     */
    public function setOutOfStock(bool $outOfStock): ProductVariant
    {
        $this->outOfStock = $outOfStock;

        return $this;
    }

    /**
     * @return bool
     */
    public function isOutOfStock(): bool
    {
        return (bool) $this->outOfStock;
    }

    /**
     * @param null|string $article
     *
     * @return ProductVariant
     */
    public function setArticle(?string $article): ProductVariant
    {
        $this->article = $article;

        return $this;
    }

    /**
     * @return string
     */
    public function getArticle(): string
    {
        return (string) $this->article;
    }
}
