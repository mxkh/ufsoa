<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class ProductTranslation
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="slug_idx", columns={"locale", "slug"})})
 * @ORM\Entity
 */
class ProductTranslation
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
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $shortDescription;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"}, updatable=false, unique_base="locale")
     * @ORM\Column(length=155, unique=false)
     */
    private $slug;

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string) $this->name;
    }

    /**
     * @param null|string $name
     *
     * @return ProductTranslation
     */
    public function setName(?string $name): ProductTranslation
    {
        $this->name = $name;

        return $this;
    }

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
     * @return ProductTranslation
     */
    public function setDescription(?string $description): ProductTranslation
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getShortDescription(): string
    {
        return (string) $this->shortDescription;
    }

    /**
     * @param null|string $shortDescription
     *
     * @return ProductTranslation
     */
    public function setShortDescription(?string $shortDescription): ProductTranslation
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * @param null|string $slug
     *
     * @return ProductTranslation
     */
    public function setSlug(?string $slug): ProductTranslation
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return (string) $this->slug;
    }
}
