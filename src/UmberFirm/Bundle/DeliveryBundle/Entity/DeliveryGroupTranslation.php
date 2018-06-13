<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\DeliveryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Class DeliveryGroupTranslation
 *
 * @package UmberFirm\Bundle\DeliveryBundle\Entity
 *
 * @ORM\Entity
 */
class DeliveryGroupTranslation
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
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

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
     * @return DeliveryGroupTranslation
     */
    public function setName(?string $name): DeliveryGroupTranslation
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
     * @param null|string $description
     *
     * @return DeliveryGroupTranslation
     */
    public function setDescription(?string $description): DeliveryGroupTranslation
    {
        $this->description = $description;

        return $this;
    }
}
