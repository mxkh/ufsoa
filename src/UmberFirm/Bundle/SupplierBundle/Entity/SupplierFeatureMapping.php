<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Bundle\ProductBundle\Entity\FeatureValue;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class SupplierFeatureMapping
 *
 * @package UmberFirm\Bundle\SupplierBundle\Entity
 *
 * @ORM\Table(name="supplier_feature_mapping",uniqueConstraints={@ORM\UniqueConstraint(name="feature_mapping_idx", columns={"supplier_id", "supplier_feature_key", "supplier_feature_value"})})
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\SupplierBundle\Repository\SupplierFeatureMappingRepository")
 */
class SupplierFeatureMapping implements UuidEntityInterface
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
     * @ORM\Column(name="supplier_feature_key", type="string", length=155)
     */
    private $supplierFeatureKey;

    /**
     * @var string
     *
     * @ORM\Column(name="supplier_feature_value", type="string", length=155)
     */
    private $supplierFeatureValue;

    /**
     * @var FeatureValue
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ProductBundle\Entity\FeatureValue")
     * @ORM\JoinColumn(nullable=true)
     */
    private $featureValue;

    /**
     * @param null|Supplier $supplier
     *
     * @return SupplierFeatureMapping
     */
    public function setSupplier(?Supplier $supplier): SupplierFeatureMapping
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
     * @param null|string $supplierFeatureKey
     *
     * @return SupplierFeatureMapping
     */
    public function setSupplierFeatureKey(?string $supplierFeatureKey): SupplierFeatureMapping
    {
        $this->supplierFeatureKey = $supplierFeatureKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getSupplierFeatureKey(): string
    {
        return (string)$this->supplierFeatureKey;
    }

    /**
     * @param null|string $supplierFeatureValue
     *
     * @return SupplierFeatureMapping
     */
    public function setSupplierFeatureValue(?string $supplierFeatureValue): SupplierFeatureMapping
    {
        $this->supplierFeatureValue = $supplierFeatureValue;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getSupplierFeatureValue(): ?string
    {
        return $this->supplierFeatureValue;
    }

    /**
     * @param null|FeatureValue $featureValue
     *
     * @return SupplierFeatureMapping
     */
    public function setFeatureValue(?FeatureValue $featureValue): SupplierFeatureMapping
    {
        $this->featureValue = $featureValue;

        return $this;
    }

    /**
     * @return null|FeatureValue
     */
    public function getFeatureValue(): ?FeatureValue
    {
        return $this->featureValue;
    }
}
