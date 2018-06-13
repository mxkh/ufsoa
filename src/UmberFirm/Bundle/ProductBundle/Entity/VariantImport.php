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
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ProductBundle\Repository\VariantImportRepository")
 *
 * @ORM\Table(
 *     uniqueConstraints={@ORM\UniqueConstraint(name="variant_import_idx", columns={"shop_id", "supplier_id", "supplier_reference"})}
 * )
 */
class VariantImport implements UuidEntityInterface
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
     * @var ProductVariant
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ProductBundle\Entity\ProductVariant")
     */
    private $productVariant;

    /**
     * @var Shop
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\Shop")
     */
    private $shop;

    /**
     * @return ProductVariant|null
     */
    public function getProductVariant(): ?ProductVariant
    {
        return $this->productVariant;
    }

    /**
     * @param null|ProductVariant $productVariant
     *
     * @return VariantImport
     */
    public function setProductVariant(?ProductVariant $productVariant): VariantImport
    {
        $this->productVariant = $productVariant;

        return $this;
    }

    /**
     * @param null|Supplier $supplier
     *
     * @return VariantImport
     */
    public function setSupplier(?Supplier $supplier): VariantImport
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
     * @return VariantImport
     */
    public function setSupplierReference(?string $supplierReference): VariantImport
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
     * @return VariantImport
     */
    public function setShop(?Shop $shop): VariantImport
    {
        $this->shop = $shop;

        return $this;
    }
}
