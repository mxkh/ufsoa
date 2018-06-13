<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class ProductSeo
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ProductBundle\Repository\ProductSeoRepository")
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="product_seo_idx", columns={"product_id", "shop_id"})})
 */
class ProductSeo implements UuidEntityInterface
{
    use UuidTrait;
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ProductBundle\Entity\Product")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @var Shop
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\Shop")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shop;

    /**
     * @param string $title
     * @param string $locale
     *
     * @return ProductSeo
     */
    public function setTitle(string $title, string $locale): ProductSeo
    {
        /** @var Translation|ProductSeoTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setTitle($title);

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        /** @var Translation|ProductSeoTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getTitle();
    }

    /**
     * @param string $description
     * @param string $locale
     *
     * @return ProductSeo
     */
    public function setDescription(string $description, string $locale): ProductSeo
    {
        /** @var Translation|ProductSeoTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setDescription($description);

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        /** @var Translation|ProductSeoTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getDescription();
    }

    /**
     * @param string $keywords
     * @param string $locale
     *
     * @return ProductSeo
     */
    public function setKeywords(string $keywords, string $locale): ProductSeo
    {
        /** @var Translation|ProductSeoTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setKeywords($keywords);

        return $this;
    }

    /**
     * @return string
     */
    public function getKeywords(): string
    {
        /** @var Translation|ProductSeoTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getKeywords();
    }

    /**
     * @return null|Product
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param null|Product $product
     *
     * @return ProductSeo
     */
    public function setProduct(?Product $product): ProductSeo
    {
        $this->product = $product;

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
     * @return ProductSeo
     */
    public function setShop(?Shop $shop): ProductSeo
    {
        $this->shop = $shop;

        return $this;
    }
}
