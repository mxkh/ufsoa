<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class SupplierManufacturerMapping
 *
 * @package UmberFirm\Bundle\SupplierBundle\Entity
 *
 * @ORM\Table(name="supplier_manufacturer_mapping",uniqueConstraints={@ORM\UniqueConstraint(name="manufacturer_mapping_idx", columns={"supplier_id", "supplier_manufacturer"})})
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\SupplierBundle\Repository\SupplierManufacturerMappingRepository")
 */
class SupplierManufacturerMapping implements UuidEntityInterface
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
     * @ORM\Column(name="supplier_manufacturer", type="string", length=155)
     */
    private $supplierManufacturer;

    /**
     * @var Manufacturer
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer")
     * @ORM\JoinColumn(nullable=true)
     */
    private $manufacturer;

    /**
     * Set supplier
     *
     * @param null|Supplier $supplier
     *
     * @return SupplierManufacturerMapping
     */
    public function setSupplier(?Supplier $supplier): SupplierManufacturerMapping
    {
        $this->supplier = $supplier;

        return $this;
    }

    /**
     * Get supplier
     *
     * @return Supplier
     */
    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    /**
     * Set supplierManufacturer
     *
     * @param null|string $supplierManufacturer
     *
     * @return SupplierManufacturerMapping
     */
    public function setSupplierManufacturer(?string $supplierManufacturer): SupplierManufacturerMapping
    {
        $this->supplierManufacturer = $supplierManufacturer;

        return $this;
    }

    /**
     * Get supplierManufacturer
     *
     * @return string
     */
    public function getSupplierManufacturer(): string
    {
        return (string)$this->supplierManufacturer;
    }

    /**
     * Set manufacturer
     *
     * @param null|Manufacturer $manufacturer
     *
     * @return SupplierManufacturerMapping
     */
    public function setManufacturer(?Manufacturer $manufacturer): SupplierManufacturerMapping
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    /**
     * Get manufacturer
     *
     * @return null|Manufacturer
     */
    public function getManufacturer(): ?Manufacturer
    {
        return $this->manufacturer;
    }
}
