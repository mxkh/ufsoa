<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class Attribute
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ProductBundle\Repository\AttributeRepository")
 */
class Attribute implements UuidEntityInterface
{
    use UuidTrait;
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @var AttributeGroup
     *
     * @ORM\ManyToOne(
     *     targetEntity="UmberFirm\Bundle\ProductBundle\Entity\AttributeGroup",
     *     inversedBy="attributes"
     * )
     */
    private $attributeGroup;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $color;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false, options={"default": 0})
     *
     * @Gedmo\SortablePosition
     */
    private $position;

    /**
     * @return null|AttributeGroup
     */
    public function getAttributeGroup(): ?AttributeGroup
    {
        return $this->attributeGroup;
    }

    /**
     * @param AttributeGroup|null $attributeGroup
     *
     * @return Attribute
     */
    public function setAttributeGroup(?AttributeGroup $attributeGroup): Attribute
    {
        $this->attributeGroup = $attributeGroup;
        if (null !== $this->attributeGroup) {
            $attributeGroup->addAttributes($this);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return (string) $this->color;
    }

    /**
     * @param null|string $color
     *
     * @return Attribute
     */
    public function setColor(?string $color): Attribute
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return (int) $this->position;
    }

    /**
     * @param int|null $position
     *
     * @return Attribute
     */
    public function setPosition(?int $position): Attribute
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Proxy method for translations
     *
     * @param null|string $locale
     *
     * @return string
     */
    public function getName(?string $locale = null): string
    {
        /** @var Translation|AttributeTranslation $translation */
        $translation = $this->translate($locale, true);

        return $translation->getName();
    }

    /**
     * Proxy Translation Method
     *
     * @param string $name
     * @param string $locale
     *
     * @return Attribute
     */
    public function setName(string $name, string $locale): Attribute
    {
        /** @var Translation|AttributeTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setName($name);

        return $this;
    }

    /**
     * Proxy method for translations
     *
     * @param null|string $locale
     *
     * @return string
     */
    public function getSlug(?string $locale = null): string
    {
        /** @var Translation|AttributeTranslation $translation */
        $translation = $this->translate($locale, true);

        return $translation->getSlug();
    }

    /**
     * Proxy Translation Method
     *
     * @param string $slug
     * @param string $locale
     *
     * @return Attribute
     */
    public function setSlug(string $slug, string $locale): Attribute
    {
        /** @var Translation|AttributeTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setSlug($slug);

        return $this;
    }
}
