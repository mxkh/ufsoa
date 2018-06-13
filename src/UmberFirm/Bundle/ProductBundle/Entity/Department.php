<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\Store;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Class Department
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ProductBundle\Repository\DepartmentRepository")
 */
class Department implements UuidEntityInterface
{
    use UuidTrait;
    use TimestampableEntity;

    /**
     * @var ProductVariant
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ProductBundle\Entity\ProductVariant", inversedBy="departments")
     */
    private $productVariant;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    private $article;

    /**
     * This type of product code is specific to Europe and Japan, but is widely used internationally.
     * It is a superset of the UPC code: all products marked with an EAN will be accepted in North America.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=13, nullable=true)
     */
    private $ean13;

    /**
     * This type of product code is widely used in the:
     * United States, Canada, the United Kingdom, Australia, New Zealand and in other countries.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=12, nullable=true)
     */
    private $upc;

    /**
     * @var float
     *
     * @ORM\Column(type="float", precision=6, length=20, nullable=false, options={"default": "0.000000"})
     */
    private $price;

    /**
     * @var float
     *
     * @ORM\Column(type="float", precision=6, length=20, nullable=false, options={"default": "0.000000"})
     */
    private $salePrice;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false, options={"default": 0})
     */
    private $quantity = 0;

    /**
     * @var Supplier
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\SupplierBundle\Entity\Supplier")
     */
    private $supplier;

    /**
     * @var Store
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\Store")
     */
    private $store;

    /**
     * @var Shop
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\Shop")
     */
    private $shop;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false, options={"default": 0})
     */
    private $priority = 0;

    /**
     * @return string
     */
    public function getArticle(): string
    {
        return (string) $this->article;
    }

    /**
     * @param null|string $article
     *
     * @return Department
     */
    public function setArticle(?string $article): Department
    {
        $this->article = $article;

        return $this;
    }

    /**
     * @return string
     */
    public function getEan13(): string
    {
        return (string) $this->ean13;
    }

    /**
     * @param null|string $ean13
     *
     * @return Department
     */
    public function setEan13(?string $ean13): Department
    {
        $this->ean13 = $ean13;

        return $this;
    }

    /**
     * @return string
     */
    public function getUpc(): string
    {
        return (string) $this->upc;
    }

    /**
     * @param null|string $upc
     *
     * @return Department
     */
    public function setUpc(?string $upc): Department
    {
        $this->upc = $upc;

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
     * @return Department
     */
    public function setPrice(?float $price): Department
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
     * @return Department
     */
    public function setSalePrice(?float $salePrice): Department
    {
        $this->salePrice = $salePrice;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return (int) $this->quantity;
    }

    /**
     * @param null|int $quantity
     *
     * @return Department
     */
    public function setQuantity(?int $quantity): Department
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return Supplier|null
     */
    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    /**
     * @param Supplier|null $supplier
     *
     * @return Department
     */
    public function setSupplier(?Supplier $supplier): Department
    {
        $this->supplier = $supplier;

        return $this;
    }

    /**
     * @return Store|null
     */
    public function getStore(): ?Store
    {
        return $this->store;
    }

    /**
     * @param Store|null $store
     *
     * @return Department
     */
    public function setStore(?Store $store): Department
    {
        $this->store = $store;

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
     * @return Department
     */
    public function setShop(?Shop $shop): Department
    {
        $this->shop = $shop;

        return $this;
    }

    /**
     * @param null|ProductVariant $productVariant
     *
     * @return Department
     */
    public function setProductVariant(?ProductVariant $productVariant): Department
    {
        $this->productVariant = $productVariant;

        return $this;
    }

    /**
     * @return null|ProductVariant
     */
    public function getProductVariant(): ?ProductVariant
    {
        return $this->productVariant;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return (int) $this->priority;
    }

    /**
     * @param null|int $priority
     *
     * @return $this
     */
    public function setPriority(?int $priority)
    {
        $this->priority = $priority;

        return $this;
    }
}
