<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;
use UmberFirm\Bundle\CategoryBundle\Entity\Category;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Class Product
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ProductBundle\Repository\ProductRepository")
 */
class Product implements
    UuidEntityInterface,
    ProductFeatureAwareInterface,
    ProductVariantAwareInterface,
    ProductMediaAwareInterface
{
    use UuidTrait;
    use ORMBehaviors\Translatable\Translatable;
    use TimestampableEntity;

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
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false, options={"default":false})
     */
    private $isHidden = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false, options={"default":false})
     */
    private $isNew = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false, options={"default":false})
     */
    private $isPreOrder = false;

    /**
     * @var double
     *
     * @ORM\Column(type="float", precision=6, length=20, nullable=true)
     */
    private $salePrice;

    /**
     * @var double
     *
     * @ORM\Column(type="float", precision=6, length=20, nullable=true)
     */
    private $price;

    /**
     * @var Shop
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\Shop")
     */
    private $shop;

    /**
     * @var Manufacturer
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer")
     * @ORM\JoinColumn(nullable=true)
     */
    private $manufacturer;

    /**
     * @var Collection|ProductFeature[]
     *
     * @ORM\OneToMany(
     *     targetEntity="UmberFirm\Bundle\ProductBundle\Entity\ProductFeature",
     *     mappedBy="product",
     *     cascade={"all"}
     * )
     */
    private $productFeatures;

    /**
     * @var Collection|ProductVariant[]
     *
     * @ORM\OneToMany(
     *     targetEntity="UmberFirm\Bundle\ProductBundle\Entity\ProductVariant",
     *     mappedBy="product",
     *     cascade={"all"}
     * )
     */
    private $productVariants;

    /**
     * @var ProductMedia[]|Collection
     *
     * @ORM\OneToMany(targetEntity="UmberFirm\Bundle\ProductBundle\Entity\ProductMedia", mappedBy="product")
     */
    private $medias;

    /**
     * @var Collection|Category[]
     *
     * @ORM\ManyToMany(targetEntity="UmberFirm\Bundle\CategoryBundle\Entity\Category", inversedBy="products")
     */
    private $categories;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    private $reference;

    /**
     * @var bool
     */
    private $isElastica = true;

    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->productFeatures = new ArrayCollection();
        $this->productVariants = new ArrayCollection();
        $this->medias = new ArrayCollection();
    }

    /**
     * Proxy method for translations
     *
     * @return string
     */
    public function getName(): string
    {
        /** @var Translation|ProductTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getName();
    }

    /**
     * Proxy Translation Method
     *
     * @param string $name
     * @param string $locale
     *
     * @return Product
     */
    public function setName(string $name, string $locale): Product
    {
        /** @var Translation|ProductTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setName($name);

        return $this;
    }

    /**
     * Proxy Translation Method
     *
     * @return string
     */
    public function getSlug(): string
    {
        /** @var Translation|ProductTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getSlug();
    }

    /**
     * Proxy Translation Method
     *
     * @param string $slug
     * @param string $locale
     *
     * @return Product
     */
    public function setSlug(string $slug, string $locale): Product
    {
        /** @var Translation|ProductTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setSlug($slug);

        return $this;
    }

    /**
     * Proxy Translation Method
     *
     * @return string
     */
    public function getDescription(): string
    {
        /** @var Translation|ProductTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getDescription();
    }

    /**
     * Proxy Translation Method
     *
     * @param string $description
     * @param string $locale
     *
     * @return Product
     */
    public function setDescription(string $description, string $locale): Product
    {
        /** @var Translation|ProductTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setDescription($description);

        return $this;
    }

    /**
     * Proxy Translation Method
     *
     * @return string
     */
    public function getShortDescription()
    {
        /** @var Translation|ProductTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getShortDescription();
    }

    /**
     * Proxy Translation Method
     *
     * @param string $shortDescription
     * @param string $locale
     *
     * @return Product
     */
    public function setShortDescription(string $shortDescription, string $locale): Product
    {
        /** @var Translation|ProductTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setShortDescription($shortDescription);

        return $this;
    }

    /**
     * @param null|string $article
     *
     * @return Product
     */
    public function setArticle(?string $article): Product
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

    /**
     * @return null|Manufacturer
     */
    public function getManufacturer(): ?Manufacturer
    {
        return $this->manufacturer;
    }

    /**
     * @param Manufacturer|null $manufacturer
     *
     * @return Product
     */
    public function setManufacturer(?Manufacturer $manufacturer): Product
    {
        $this->manufacturer = $manufacturer;

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
     * @param bool|null $outOfStock
     *
     * @return Product
     */
    public function setOutOfStock(bool $outOfStock): Product
    {
        $this->outOfStock = $outOfStock;

        return $this;
    }

    /**
     * @param bool|null $isHidden
     *
     * @return Product
     */
    public function setIsHidden(bool $isHidden): Product
    {
        $this->isHidden = $isHidden;

        return $this;
    }

    /**
     * @return bool
     */
    public function isHidden(): bool
    {
        return (bool) $this->isHidden;
    }

    /**
     * Get sale price
     *
     * @return float
     */
    public function getSalePrice(): float
    {
        return (float) $this->salePrice;
    }

    /**
     * Set sale price
     *
     * @param null|float $salePrice
     *
     * @return Product
     */
    public function setSalePrice(?float $salePrice): Product
    {
        $this->salePrice = $salePrice;

        return $this;
    }

    /**
     * Get max price
     *
     * @return float
     */
    public function getPrice(): float
    {
        return (float) $this->price;
    }

    /**
     * Set price
     *
     * @param null|float $price
     *
     * @return Product
     */
    public function setPrice(?float $price): Product
    {
        $this->price = $price;

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
     * @return Product
     */
    public function setShop(?Shop $shop): Product
    {
        $this->shop = $shop;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * @param null|Category $category
     *
     * @return Product
     */
    public function addCategory(Category $category): Product
    {
        if (false === $this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addProduct($this);
        }

        return $this;
    }

    /**
     * @param Category $category
     *
     * @return Product
     */
    public function removeCategory(Category $category): Product
    {
        if (true === $this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getProductFeatures(): Collection
    {
        return $this->productFeatures;
    }

    /**
     * {@inheritdoc}
     */
    public function addProductFeature(ProductFeature $productFeature): Product
    {
        if (false === $this->productFeatures->contains($productFeature)) {
            $productFeature->setProduct($this);
            $this->productFeatures->add($productFeature);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeProductFeature(ProductFeature $productFeature): Product
    {
        if (true === $this->productFeatures->contains($productFeature)) {
            $this->productFeatures->removeElement($productFeature);
            $productFeature->setProduct(null);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getProductVariants(): Collection
    {
        return $this->productVariants;
    }

    /**
     * {@inheritdoc}
     */
    public function addProductVariant(ProductVariant $productVariant): Product
    {
        if (false === $this->productVariants->contains($productVariant)) {
            $productVariant->setProduct($this);
            $this->productVariants->add($productVariant);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeProductVariant(ProductVariant $productVariant): Product
    {
        if (true === $this->productVariants->contains($productVariant)) {
            $this->productVariants->removeElement($productVariant);
            $productVariant->setProduct(null);
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
    public function addMedia(ProductMedia $media): Product
    {
        if (false === $this->medias->contains($media)) {
            $this->medias->add($media);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeMedia(ProductMedia $media): Product
    {
        if (true === $this->medias->contains($media)) {
            $this->medias->removeElement($media);
            $media->setProduct(null);
        }

        return $this;
    }

    /**
     * @param null|string $reference
     *
     * @return Product
     */
    public function setReference(?string $reference): Product
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return string
     */
    public function getReference(): string
    {
        return (string) $this->reference;
    }

    /**
     * @param bool $isElastica
     *
     * @return Product
     */
    public function setIsElastica(bool $isElastica): Product
    {
        $this->isElastica = $isElastica;

        return $this;
    }

    /**
     * @return bool
     */
    public function isElastica(): bool
    {
        return (bool) $this->isElastica;
    }

    /**
     * @param bool|null $isNew
     *
     * @return Product
     */
    public function setIsNew(?bool $isNew): Product
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
     * @param bool|null $isPreOrder
     *
     * @return Product
     */
    public function setIsPreOrder(?bool $isPreOrder): Product
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
}
