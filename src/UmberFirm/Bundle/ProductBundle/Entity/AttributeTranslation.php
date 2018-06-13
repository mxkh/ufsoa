<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class AttributeTranslation
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ProductBundle\Repository\AttributeTranslationRepository")
 */
class AttributeTranslation
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
     * @ORM\Column(type="string", length=128, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
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
     * @return AttributeTranslation
     */
    public function setName(?string $name): AttributeTranslation
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param null|string $slug
     *
     * @return AttributeTranslation
     */
    public function setSlug(?string $slug): AttributeTranslation
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
