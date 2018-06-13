<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class MediaEnum
 *
 * @package UmberFirm\Bundle\MediaBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\MediaBundle\Repository\MediaEnumRepository")
 */
class MediaEnum implements UuidEntityInterface
{
    use UuidTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64)
     */
    private $name;

    /**
     * @var ArrayCollection|Media[]
     *
     * @ORM\OneToMany(
     *     targetEntity="UmberFirm\Bundle\MediaBundle\Entity\Media",
     *     mappedBy="mediaEnum"
     * )
     */
    private $medias;

    /**
     * @var ArrayCollection|MimeType[]
     *
     * @ORM\OneToMany(
     *     targetEntity="UmberFirm\Bundle\MediaBundle\Entity\MimeType",
     *     mappedBy="mediaEnum"
     * )
     */
    private $mimeTypes;

    /**
     * MediaEnum constructor.
     */
    public function __construct()
    {
        $this->medias = new ArrayCollection();
        $this->mimeTypes = new ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string|null $name
     *
     * @return MediaEnum
     */
    public function setName(string $name): MediaEnum
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return Collection|Media[]
     */
    public function getMedias(): Collection
    {
        return $this->medias;
    }

    /**
     * @return Collection|MimeType[]
     */
    public function getMimeTypes(): Collection
    {
        return $this->mimeTypes;
    }
}
