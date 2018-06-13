<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity
 */
class SocialProfileEnumTranslation
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
    private $alias;

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return (string) $this->alias;
    }

    /**
     * @param null|string $alias
     *
     * @return SocialProfileEnumTranslation
     */
    public function setAlias(?string $alias): SocialProfileEnumTranslation
    {
        $this->alias = $alias;

        return $this;
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
     * @return SocialProfileEnumTranslation
     */
    public function setName(?string $name): SocialProfileEnumTranslation
    {
        $this->name = $name;

        return $this;
    }
}
