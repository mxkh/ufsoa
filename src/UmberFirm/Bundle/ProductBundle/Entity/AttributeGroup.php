<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class AttributeGroup
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ProductBundle\Repository\AttributeGroupRepository")
 */
class AttributeGroup implements UuidEntityInterface
{
    use UuidTrait;
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @var null|string
     *
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $code;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false, options={"default":false})
     */
    private $isColorGroup = false;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false, options={"default":0})
     * @Gedmo\SortablePosition
     */
    private $position;

    /**
     * The best approach for EAGER LOADING is setup fetch mode in QueryBuilder
     * It's adds more flexibility, because you can choose in which query use EAGER LOADING
     * And more performance, because of the decreased cost of the SQL JOIN
     * But in my case when I use setFetchMode(); in QueryBuilder the ramsey/uuid not converted to string automatically
     * And SQL query looks like a SELECT * FROM t WHERE id IN ('Object(Ramsey\\Uuid\\Uuid)', 'Object(Ramsey\\Uuid\\Uuid)');
     *
     * @var AttributeGroupEnum
     *
     * @ORM\ManyToOne(
     *     targetEntity="UmberFirm\Bundle\ProductBundle\Entity\AttributeGroupEnum",
     *     inversedBy="attributeGroups",
     *     fetch="EAGER"
     * )
     */
    private $attributeGroupEnum;

    /**
     * @var Collection|Attribute[]
     *
     * @ORM\OneToMany(
     *     targetEntity="UmberFirm\Bundle\ProductBundle\Entity\Attribute",
     *     mappedBy="attributeGroup",
     *     cascade={"all"}
     * )
     */
    private $attributes;

    /**
     * AttributeGroup constructor.
     */
    public function __construct()
    {
        $this->attributes = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return (string) $this->code;
    }

    /**
     * @param null|string $code
     *
     * @return AttributeGroup
     */
    public function setCode(?string $code): AttributeGroup
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return null|bool Returns null if no data is present @see https://goo.gl/f8uV5B
     */
    public function getIsColorGroup(): ?bool
    {
        return $this->isColorGroup;
    }

    /**
     * @param bool $isColorGroup
     *
     * @return AttributeGroup
     */
    public function setIsColorGroup(bool $isColorGroup): AttributeGroup
    {
        $this->isColorGroup = $isColorGroup;

        return $this;
    }

    /**
     * @return null|AttributeGroupEnum
     */
    public function getAttributeGroupEnum(): ?AttributeGroupEnum
    {
        return $this->attributeGroupEnum;
    }

    /**
     * @param null|AttributeGroupEnum $attributeGroupEnum
     *
     * @return AttributeGroup
     */
    public function setAttributeGroupEnum(?AttributeGroupEnum $attributeGroupEnum): AttributeGroup
    {
        $this->attributeGroupEnum = $attributeGroupEnum;
        if (null !== $attributeGroupEnum) {
            $attributeGroupEnum->addAttributeGroup($this);
        }

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
     * @param int $position
     *
     * @return AttributeGroup
     */
    public function setPosition(?int $position): AttributeGroup
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @param Attribute $attribute
     *
     * @return AttributeGroup
     */
    public function addAttributes(Attribute $attribute): AttributeGroup
    {
        if (false === $this->attributes->contains($attribute)) {
            $this->attributes->add($attribute);
            $attribute->setAttributeGroup($this);
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    /**
     * Proxy translation method.
     *
     * @param string|null $locale
     *
     * @return string
     */
    public function getName(string $locale = null): string
    {
        /** @var Translation|AttributeGroupTranslation $translation */
        $translation = $this->translate($locale, true);

        return $translation->getName();
    }

    /**
     * Proxy Translation Method
     *
     * @param string $name
     * @param string $locale
     *
     * @return AttributeGroup
     */
    public function setName(string $name, string $locale): AttributeGroup
    {
        /** @var Translation|AttributeGroupTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setName($name);

        return $this;
    }

    /**
     * Proxy translation method
     *
     * @param string|null $locale
     *
     * @return string
     */
    public function getPublicName(string $locale = null): string
    {
        /** @var Translation|AttributeGroupTranslation $translation */
        $translation = $this->translate($locale, true);

        return $translation->getPublicName();
    }

    /**
     * Proxy Translation Method
     *
     * @param string $publicName
     * @param string $locale
     *
     * @return AttributeGroup
     */
    public function setPublicName(string $publicName, string $locale): AttributeGroup
    {
        /** @var Translation|AttributeGroupTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setPublicName($publicName);

        return $this;
    }
}
