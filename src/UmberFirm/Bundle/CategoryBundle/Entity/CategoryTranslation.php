<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CategoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class CategoryTranslation
 *
 * @package UmberFirm\Bundle\CategoryBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\CategoryBundle\Repository\CategoryTranslationRepository")
 */
class CategoryTranslation
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
     * @ORM\Column(type="string", length=155)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"title"}, updatable=false)
     *
     * @ORM\Column(length=155, unique=true)
     */
    private $slug;

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param null|string $description
     *
     * @return CategoryTranslation
     */
    public function setDescription(?string $description): CategoryTranslation
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param null|string $slug
     *
     * @return CategoryTranslation
     */
    public function setSlug(?string $slug): CategoryTranslation
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }
}
