<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CategoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;

/**
 * Class CategorySeo
 *
 * @package UmberFirm\Bundle\CategoryBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\CategoryBundle\Repository\CategorySeoRepository")
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="category_seo_idx", columns={"category_id", "shop_id"})})
 */
class CategorySeo implements UuidEntityInterface
{
    use UuidTrait;
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\CategoryBundle\Entity\Category")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

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
     * @return CategorySeo
     */
    public function setTitle(string $title, string $locale): CategorySeo
    {
        /** @var Translation|CategorySeoTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setTitle($title);

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        /** @var Translation|CategorySeoTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getTitle();
    }

    /**
     * @param string $description
     * @param string $locale
     *
     * @return CategorySeo
     */
    public function setDescription(string $description, string $locale): CategorySeo
    {
        /** @var Translation|CategorySeoTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setDescription($description);

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        /** @var Translation|CategorySeoTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getDescription();
    }

    /**
     * @param string $keywords
     * @param string $locale
     *
     * @return CategorySeo
     */
    public function setKeywords(string $keywords, string $locale): CategorySeo
    {
        /** @var Translation|CategorySeoTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setKeywords($keywords);

        return $this;
    }

    /**
     * @return string
     */
    public function getKeywords(): string
    {
        /** @var Translation|CategorySeoTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getKeywords();
    }

    /**
     * @param null|Category $category
     *
     * @return CategorySeo
     */
    public function setCategory(?Category $category): CategorySeo
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return null|Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
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
     * @return CategorySeo
     */
    public function setShop(?Shop $shop): CategorySeo
    {
        $this->shop = $shop;

        return $this;
    }
}
