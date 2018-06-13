<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity
 */
class ContactEnumTranslation
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
    private $value;

    /**
     * @return string
     */
    public function getValue(): string
    {
        return (string) $this->value;
    }

    /**
     * @param null|string $value
     *
     * @return ContactEnumTranslation
     */
    public function setValue(?string $value): ContactEnumTranslation
    {
        $this->value = $value;

        return $this;
    }
}
