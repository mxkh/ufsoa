<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use UmberFirm\Bundle\MediaBundle\Entity\MediaEnum;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\SupplierBundle\Services\Format\FormatFactory;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class Import
 *
 * @package UmberFirm\Bundle\SupplierBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\SupplierBundle\Repository\ImportRepository")
 */
class Import implements UuidEntityInterface
{
    use UuidTrait;
    use TimestampableEntity;

    const STATUS_INACTIVE = 0;
    const STATUS_CREATED = 10;
    const STATUS_SUCCESS = 1;
    const STATUS_INVALID = 2;
    const STATUS_ERROR = 3;
    const STATUS_IMPORTING = 4;

    /**
     * @var MediaEnum
     *
     * @ORM\ManyToOne(
     *     targetEntity="UmberFirm\Bundle\MediaBundle\Entity\MediaEnum",
     *     inversedBy="medias"
     * )
     */
    private $mediaEnum;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155)
     */
    private $version;

    /**
     * @var Supplier
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\SupplierBundle\Entity\Supplier")
     */
    private $supplier;

    /**
     * @var Shop
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\Shop")
     */
    private $shop;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $mimeType;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $extension;

    /**
     * @var integer
     *
     * @ORM\Column(type="smallint", nullable=false, options={"default":0})
     */
    private $status = self::STATUS_INACTIVE;

    /**
     * @var UploadedFile
     */
    private $file;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $countProducts;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $countVariants;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $countDepartments;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $importedProducts;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $importedVariants;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $importedDepartments;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $updatedProducts;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $updatedVariants;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $departmentsOutOfStock;

    /**
     * @param null|MediaEnum $mediaEnum
     *
     * @return Import
     */
    public function setMediaEnum(?MediaEnum $mediaEnum): Import
    {
        $this->mediaEnum = $mediaEnum;

        return $this;
    }

    /**
     * @return null|MediaEnum
     */
    public function getMediaEnum(): ?MediaEnum
    {
        return $this->mediaEnum;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return (string) $this->version;
    }

    /**
     * @param null|string $version
     *
     * @return Import
     */
    public function setVersion(?string $version): Import
    {
        $this->version = $version;

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
     * @param null|Supplier $supplier
     *
     * @return Import
     */
    public function setSupplier(?Supplier $supplier): Import
    {
        $this->supplier = $supplier;

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
     * @return Import
     */
    public function setShop(?Shop $shop): Import
    {
        $this->shop = $shop;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return (string) $this->filename;
    }

    /**
     * @param null|string $filename
     *
     * @return Import
     */
    public function setFilename(?string $filename): Import
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return (string) $this->mimeType;
    }

    /**
     * @param null|string $mimeType
     *
     * @return Import
     */
    public function setMimeType(?string $mimeType): Import
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return (string) $this->extension;
    }

    /**
     * @param null|string $extension
     *
     * @return Import
     */
    public function setExtension(?string $extension): Import
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * @return UploadedFile|null
     */
    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    /**
     * @param UploadedFile|null $file
     *
     * @return Import
     */
    public function setFile(?UploadedFile $file): Import
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @param int|null $status
     *
     * @return Import
     */
    public function setStatus(?int $status): Import
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return (int) $this->status;
    }

    /**
     * @return array
     */
    public function getVersions(): array
    {
        return array_keys(FormatFactory::versions());
    }

    /**
     * @return array
     */
    public function getStatuses(): array
    {
        return [
            self::STATUS_INACTIVE,
            self::STATUS_CREATED,
            self::STATUS_SUCCESS,
            self::STATUS_INVALID,
            self::STATUS_ERROR,
        ];
    }

    /**
     * @param int $countProducts
     *
     * @return Import
     */
    public function setCountProducts(int $countProducts): Import
    {
        $this->countProducts = $countProducts;

        return $this;
    }

    /**
     * @return int
     */
    public function getCountProducts(): int
    {
        return $this->countProducts;
    }

    /**
     * @param int $countVariants
     *
     * @return Import
     */
    public function setCountVariants(int $countVariants): Import
    {
        $this->countVariants = $countVariants;

        return $this;
    }

    /**
     * @return int
     */
    public function getCountVariants(): int
    {
        return $this->countVariants;
    }

    /**
     * @param int $countDepartments
     *
     * @return Import
     */
    public function setCountDepartments(int $countDepartments): Import
    {
        $this->countDepartments = $countDepartments;

        return $this;
    }

    /**
     * @return int
     */
    public function getCountDepartments(): int
    {
        return $this->countDepartments;
    }

    /**
     * @param int $importedProducts
     *
     * @return Import
     */
    public function setImportedProducts(int $importedProducts): Import
    {
        $this->importedProducts = $importedProducts;

        return $this;
    }

    /**
     * @return int
     */
    public function getImportedProducts(): int
    {
        return $this->importedProducts;
    }

    /**
     * @param int $importedVariants
     *
     * @return Import
     */
    public function setImportedVariants(int $importedVariants): Import
    {
        $this->importedVariants = $importedVariants;

        return $this;
    }

    /**
     * @return int
     */
    public function getImportedVariants(): int
    {
        return $this->importedVariants;
    }

    /**
     * @param int $importedDepartments
     *
     * @return Import
     */
    public function setImportedDepartments(int $importedDepartments): Import
    {
        $this->importedDepartments = $importedDepartments;

        return $this;
    }

    /**
     * @return int
     */
    public function getImportedDepartments(): int
    {
        return $this->importedDepartments;
    }

    /**
     * @param int $updatedProducts
     *
     * @return Import
     */
    public function setUpdatedProducts(int $updatedProducts): Import
    {
        $this->updatedProducts = $updatedProducts;

        return $this;
    }

    /**
     * @return int
     */
    public function getUpdatedProducts(): int
    {
        return $this->updatedProducts;
    }

    /**
     * @param int $updatedVariants
     *
     * @return Import
     */
    public function setUpdatedVariants(int $updatedVariants): Import
    {
        $this->updatedVariants = $updatedVariants;

        return $this;
    }

    /**
     * @return int
     */
    public function getUpdatedVariants(): int
    {
        return $this->updatedVariants;
    }

    /**
     * @param int $departmentsOutOfStock
     *
     * @return Import
     */
    public function setDepartmentsOutOfStock(int $departmentsOutOfStock): Import
    {
        $this->departmentsOutOfStock = $departmentsOutOfStock;

        return $this;
    }

    /**
     * @return int
     */
    public function getDepartmentsOutOfStock(): int
    {
        return $this->departmentsOutOfStock;
    }
}
