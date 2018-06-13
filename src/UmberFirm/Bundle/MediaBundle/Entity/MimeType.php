<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class MimeType
 *
 * @package UmberFirm\Bundle\MediaBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\MediaBundle\Repository\MimeTypeRepository")
 */
class MimeType implements UuidEntityInterface
{
    use UuidTrait;

    /**
     * @var MediaEnum
     *
     * @ORM\ManyToOne(
     *     targetEntity="UmberFirm\Bundle\MediaBundle\Entity\MediaEnum",
     *     inversedBy="mimeTypes"
     * )
     * @ORM\JoinColumn(nullable=false)
     */
    private $mediaEnum;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155)
     */
    private $template;

    /**
     * @param null|MediaEnum $mediaEnum
     *
     * @return MimeType
     */
    public function setMediaEnum(?MediaEnum $mediaEnum): MimeType
    {
        $this->mediaEnum = $mediaEnum;

        return $this;
    }

    /**
     * @return MediaEnum|null
     */
    public function getMediaEnum(): ?MediaEnum
    {
        return $this->mediaEnum;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return MimeType
     */
    public function setName(?string $name): MimeType
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTemplate(): ?string
    {
        return $this->template;
    }

    /**
     * @param string|null $template
     *
     * @return MimeType
     */
    public function setTemplate(?string $template): MimeType
    {
        $this->template = $template;

        return $this;
    }
}
