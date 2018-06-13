<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Bundle\ShopBundle\Entity\Store;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class SupplierStoreMapping
 *
 * @package UmberFirm\Bundle\SupplierBundle\Entity
 *
 * @ORM\Table(
 *     name="supplier_store_mapping",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="store_mapping_idx", columns={"supplier_id", "supplier_store"})}
 * )
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\SupplierBundle\Repository\SupplierStoreMappingRepository")
 */
class SupplierStoreMapping implements UuidEntityInterface
{
    use UuidTrait;

    /**
     * @var Supplier
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\SupplierBundle\Entity\Supplier")
     * @ORM\JoinColumn(nullable=false)
     */
    private $supplier;

    /**
     * @var string
     *
     * @ORM\Column(name="supplier_store", type="string", length=155)
     * @ORM\JoinColumn(nullable=false)
     */
    private $supplierStore;

    /**
     * @var Store
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\Store")
     * @ORM\JoinColumn(nullable=true)
     */
    private $store;

    /**
     * Set supplier
     *
     * @param null|Supplier $supplier
     *
     * @return SupplierStoreMapping
     */
    public function setSupplier(?Supplier $supplier): SupplierStoreMapping
    {
        $this->supplier = $supplier;

        return $this;
    }

    /**
     * Get supplier
     *
     * @return null|Supplier
     */
    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    /**
     * @return string
     */
    public function getSupplierStore(): string
    {
        return (string)$this->supplierStore;
    }

    /**
     * @param null|string $supplierStore
     *
     * @return SupplierStoreMapping
     */
    public function setSupplierStore(?string $supplierStore): SupplierStoreMapping
    {
        $this->supplierStore = $supplierStore;

        return $this;
    }

    /**
     * @return null|Store
     */
    public function getStore(): ?Store
    {
        return $this->store;
    }

    /**
     * @param null|Store $store
     *
     * @return SupplierStoreMapping
     */
    public function setStore(?Store $store): SupplierStoreMapping
    {
        $this->store = $store;

        return $this;
    }
}
