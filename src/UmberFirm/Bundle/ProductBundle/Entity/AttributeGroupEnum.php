<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class AttributeGroupEnum
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ProductBundle\Repository\AttributeGroupEnumRepository")
 */
class AttributeGroupEnum implements UuidEntityInterface
{
    use UuidTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    private $name;

    /**
     * @var ArrayCollection|AttributeGroup[]
     *
     * @ORM\OneToMany(targetEntity="AttributeGroup", mappedBy="attributeGroupEnum")
     */
    private $attributeGroups;

    /**
     * AttributeGroupEnum constructor.
     */
    public function __construct()
    {
        $this->attributeGroups = new ArrayCollection();
    }

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
     * @return AttributeGroupEnum
     */
    public function setName(?string $name): AttributeGroupEnum
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param AttributeGroup $attributeGroup
     *
     * @return AttributeGroupEnum
     */
    public function addAttributeGroup(AttributeGroup $attributeGroup): AttributeGroupEnum
    {
        if (false === $this->attributeGroups->contains($attributeGroup)) {
            $this->attributeGroups->add($attributeGroup);
            $attributeGroup->setAttributeGroupEnum($this);
        }

        return $this;
    }
}
