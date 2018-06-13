<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Class SelectionTranslation
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 *
 * @ORM\Entity()
 */
class SelectionTranslation
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
     * @ORM\Column(type="text", nullable=false)
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
     * @Gedmo\Slug(fields={"name"}, updatable=false)
     *
     * @ORM\Column(length=155, unique=true)
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
     * @return SelectionTranslation
     */
    public function setName(?string $name): SelectionTranslation
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
     * @param string $description
     *
     * @return SelectionTranslation
     */
    public function setDescription(?string $description): SelectionTranslation
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return (string) $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return SelectionTranslation
     */
    public function setSlug(?string $slug): SelectionTranslation
    {
        $this->slug = $slug;

        return $this;
    }
}
