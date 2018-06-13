<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CategoryBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Tree\Traits\NestedSetEntityUuid;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Component\Doctrine\Entity\Gedmo\Sortable\SortableTrait;
use UmberFirm\Component\Doctrine\Tree\NestedSet\NestedSetEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Class Category
 *
 * @package UmberFirm\Bundle\CategoryBundle\Entity
 *
 * @Gedmo\Tree(type="nested")
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\CategoryBundle\Repository\CategoryRepository")
 */
class Category implements UuidEntityInterface, NestedSetEntityInterface
{
    use TimestampableEntity;
    use NestedSetEntityUuid;
    use UuidTrait;
    use ORMBehaviors\Translatable\Translatable;
    use SortableTrait;

    /**
     * @var Category
     *
     * @Gedmo\TreeParent
     * @Gedmo\SortableGroup
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     */
    private $parent;

    /**
     * @var Category
     *
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     * @ORM\OrderBy({"left" = "ASC"})
     */
    private $children;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    private $reference;

    /**
     * @var Shop
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\Shop", inversedBy="categories")
     */
    private $shop;

    /**
     * @var Collection|Product[]
     *
     * @ORM\ManyToMany(targetEntity="UmberFirm\Bundle\ProductBundle\Entity\Product", mappedBy="categories")
     */
    private $products;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getLeft(): int
    {
        return $this->left;
    }

    /**
     * @param int $left
     *
     * @return Category
     */
    public function setLeft(int $left): Category
    {
        $this->left = $left;

        return $this;
    }

    /**
     * @param int $level
     *
     * @return Category
     */
    public function setLevel(int $level): Category
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param int $right
     *
     * @return Category
     */
    public function setRight(int $right): Category
    {
        $this->right = $right;

        return $this;
    }

    /**
     * @return int
     */
    public function getRight(): int
    {
        return $this->right;
    }

    /**
     * @param string $root
     *
     * @return Category
     */
    public function setRoot(string $root): Category
    {
        $this->root = $root;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getRoot(): ?string
    {
        return $this->root;
    }

    /**
     * @param string $title
     * @param string $locale
     *
     * @return Category
     */
    public function setTitle(string $title, string $locale): Category
    {
        /** @var Translation|CategoryTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setTitle($title);

        return $this;
    }

    /**
     * @return null|string
     */
    public function getTitle(): ?string
    {
        /** @var Translation|CategoryTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getTitle();
    }

    /**
     * @return null|string
     */
    public function getSlug(): ?string
    {
        /** @var Translation|CategoryTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getSlug();
    }

    /**
     * @param string $description
     * @param string $locale
     *
     * @return Category
     */
    public function setDescription(string $description, string $locale): Category
    {
        /** @var Translation|CategoryTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setDescription($description);

        return $this;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        /** @var Translation|CategoryTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getDescription();
    }

    /**
     * @param Category|null $parent Defaults as null
     *
     * @return Category
     */
    public function setParent(Category $parent = null): Category
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return null|Category
     */
    public function getParent(): ?Category
    {
        return $this->parent;
    }

    /**
     * @param Category|null $children Defaults as null
     *
     * @return Category
     */
    public function setChildren(Category $children = null): Category
    {
        $this->children = $children;

        return $this;
    }

    /**
     * @return null|Category
     */
    public function getChildren(): ?Category
    {
        return $this->children;
    }

    /**
     * @return Shop|null
     */
    public function getShop(): ?Shop
    {
        return $this->shop;
    }

    /**
     * @param Shop|null $shop Defaults as null
     *
     * @return Category
     */
    public function setShop(Shop $shop = null): Category
    {
        $this->shop = $shop;

        if (null !== $this->shop) {
            $shop->addCategories($this);
        }

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * @param Product $product
     *
     * @return Category
     */
    public function addProduct(Product $product): Category
    {
        if (false === $this->products->contains($product)) {
            $this->products->add($product);
            $product->addCategory($this);
        }

        return $this;
    }

    /**
     * @param null|string $reference
     *
     * @return Category
     */
    public function setReference(?string $reference): Category
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
}
