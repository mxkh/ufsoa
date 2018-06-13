<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * ProductImport
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ProductBundle\Repository\ProductImportRepository")
 *
 * @ORM\Table(
 *     uniqueConstraints={@ORM\UniqueConstraint(name="product_import_idx", columns={"shop_id", "supplier_id", "supplier_reference"})}
 * )
 */
class ProductImport implements UuidEntityInterface
{
    use UuidTrait;

    /**
     * @var Supplier
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\SupplierBundle\Entity\Supplier")
     */
    private $supplier;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155, nullable=false)
     */
    private $supplierReference;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ProductBundle\Entity\Product")
     */
    private $product;

    /**
     * @var Shop
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\Shop")
     */
    private $shop;

    /**
     * @return Product
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param null|Product $product
     *
     * @return ProductImport
     */
    public function setProduct(?Product $product): ProductImport
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @param null|Supplier $supplier
     *
     * @return ProductImport
     */
    public function setSupplier(?Supplier $supplier): ProductImport
    {
        $this->supplier = $supplier;

        return $this;
    }

    /**
     * @return null|Supplier
     */
    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    /**
     * @param null|string $supplierReference
     *
     * @return ProductImport
     */
    public function setSupplierReference(?string $supplierReference): ProductImport
    {
        $this->supplierReference = $supplierReference;

        return $this;
    }

    /**
     * @return string
     */
    public function getSupplierReference(): string
    {
        return (string) $this->supplierReference;
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
     * @return ProductImport
     */
    public function setShop(?Shop $shop): ProductImport
    {
        $this->shop = $shop;

        return $this;
    }
}
