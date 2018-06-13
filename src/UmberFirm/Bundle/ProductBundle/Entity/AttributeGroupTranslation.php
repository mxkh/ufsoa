<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Class AttributeGroupTranslation
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 *
 * @ORM\Entity
 */
class AttributeGroupTranslation
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
     * @ORM\Column(type="string", length=128, nullable=false)
     */
    private $publicName;

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
     * @return AttributeGroupTranslation
     */
    public function setName(?string $name): AttributeGroupTranslation
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getPublicName(): string
    {
        return (string) $this->publicName;
    }

    /**
     * @param null|string $publicName
     *
     * @return AttributeGroupTranslation
     */
    public function setPublicName(?string $publicName): AttributeGroupTranslation
    {
        $this->publicName = $publicName;

        return $this;
    }
}
