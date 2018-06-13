<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Bundle\ProductBundle\Entity\Attribute;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class SupplierAttributeMapping
 *
 * @package UmberFirm\Bundle\SupplierBundle\Entity
 *
 * @ORM\Table(name="supplier_attribute_mapping",uniqueConstraints={@ORM\UniqueConstraint(name="attribute_mapping_idx", columns={"supplier_id", "supplier_attribute_key", "supplier_attribute_value"})})
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\SupplierBundle\Repository\SupplierAttributeMappingRepository")
 */
class SupplierAttributeMapping implements UuidEntityInterface
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
     * @ORM\Column(name="supplier_attribute_key", type="string", length=155)
     */
    private $supplierAttributeKey;

    /**
     * @var string
     *
     * @ORM\Column(name="supplier_attribute_value", type="string", length=155)
     */
    private $supplierAttributeValue;

    /**
     * @var Attribute
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ProductBundle\Entity\Attribute")
     * @ORM\JoinColumn(nullable=true)
     */
    private $attribute;

    /**
     * @return null|Supplier
     */
    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    /**
     * @param null|Supplier $supplier
     *
     * @return SupplierAttributeMapping
     */
    public function setSupplier(?Supplier $supplier): SupplierAttributeMapping
    {
        $this->supplier = $supplier;

        return $this;
    }

    /**
     * @param null|string $supplierAttributeKey
     *
     * @return SupplierAttributeMapping
     */
    public function setSupplierAttributeKey(?string $supplierAttributeKey): SupplierAttributeMapping
    {
        $this->supplierAttributeKey = $supplierAttributeKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getSupplierAttributeKey(): string
    {
        return (string)$this->supplierAttributeKey;
    }

    /**
     * @param null|string $supplierAttributeValue
     *
     * @return SupplierAttributeMapping
     */
    public function setSupplierAttributeValue(?string $supplierAttributeValue): SupplierAttributeMapping
    {
        $this->supplierAttributeValue = $supplierAttributeValue;

        return $this;
    }

    /**
     * @return string
     */
    public function getSupplierAttributeValue(): string
    {
        return (string)$this->supplierAttributeValue;
    }

    /**
     * @param null|Attribute $attribute
     *
     * @return SupplierAttributeMapping
     */
    public function setAttribute(?Attribute $attribute): SupplierAttributeMapping
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * @return null|Attribute
     */
    public function getAttribute(): ?Attribute
    {
        return $this->attribute;
    }
}
