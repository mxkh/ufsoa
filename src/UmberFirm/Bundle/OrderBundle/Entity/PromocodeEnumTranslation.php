<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Class PromocodeEnumTranslation
 *
 * @package UmberFirm\Bundle\OrderBundle\Entity
 *
 * @ORM\Entity
 */
class PromocodeEnumTranslation
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
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $name;

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     *
     * @return PromocodeEnumTranslation
     */
    public function setName(?string $name): PromocodeEnumTranslation
    {
        $this->name = $name;

        return $this;
    }
}
