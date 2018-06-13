<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ManufacturerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Class ManufacturerTranslation
 *
 * @package UmberFirm\Bundle\ManufacturerBundle\Entity\Translation
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ManufacturerBundle\Repository\ManufacturerTranslationRepository")
 */
class ManufacturerTranslation
{
    use ORMBehaviors\Translatable\Translation;

    /**
     * @var string
     *
     * @ORM\Column(length=155)
     */
    protected $locale;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return (string) $this->description;
    }

    /**
     * @param null|string $description
     *
     * @return ManufacturerTranslation
     */
    public function setDescription(?string $description): ManufacturerTranslation
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param string $address
     *
     * @return ManufacturerTranslation
     */
    public function setAddress(?string $address): ManufacturerTranslation
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }
}
