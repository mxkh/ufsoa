<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use UmberFirm\Bundle\ProductBundle\Entity\ProductMedia;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class Media
 *
 * @package UmberFirm\Bundle\MediaBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\MediaBundle\Repository\MediaRepository")
 */
class Media implements UuidEntityInterface
{
    use UuidTrait;
    use TimestampableEntity;

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
     * @ORM\Column(name="filename", type="string", length=255)
     */
    private $filename;

    /**
     * @var string
     *
     * @ORM\Column(name="mimeType", type="string", length=255)
     */
    private $mimeType;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=255)
     */
    private $extension;

    /**
     * @var UploadedFile
     */
    private $file;

    /**
     * @var ProductMedia[]|Collection
     *
     * @ORM\OneToMany(
     *     targetEntity="UmberFirm\Bundle\ProductBundle\Entity\ProductMedia",
     *     mappedBy="media",
     *     cascade={"remove"}
     * )
     */
    private $productMedias;

    /**
     * Media constructor.
     */
    public function __construct()
    {
        $this->productMedias = new ArrayCollection();
    }

    /**
     * Set mediaEnum
     *
     * @param null|MediaEnum $mediaEnum
     *
     * @return Media
     */
    public function setMediaEnum(?MediaEnum $mediaEnum): Media
    {
        $this->mediaEnum = $mediaEnum;

        return $this;
    }

    /**
     * Get mediaEnum
     *
     * @return MediaEnum|null
     */
    public function getMediaEnum(): ?MediaEnum
    {
        return $this->mediaEnum;
    }

    /**
     * Set filename
     *
     * @param string|null $filename
     *
     * @return Media
     */
    public function setFilename(?string $filename): Media
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * Set mimeType
     *
     * @param string|null $mimeType
     *
     * @return Media
     */
    public function setMimeType(?string $mimeType): Media
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string|null
     */
    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    /**
     * Set extension
     *
     * @param string|null $extension
     *
     * @return Media
     */
    public function setExtension(?string $extension): Media
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string|null
     */
    public function getExtension(): ?string
    {
        return $this->extension;
    }

    /**
     * @param UploadedFile|null $file
     *
     * @return Media
     */
    public function setFile(?UploadedFile $file): Media
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return UploadedFile|null
     */
    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }
}
