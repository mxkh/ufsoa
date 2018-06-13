<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Class SupplierTranslation
 *
 * @package UmberFirm\Bundle\SupplierBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\SupplierBundle\Repository\SupplierTranslationRepository")
 */
class SupplierTranslation
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
     * @ORM\Column(name="name", type="string", length=128)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

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
     * @return SupplierTranslation
     */
    public function setDescription(?string $description): SupplierTranslation
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param null|string $name
     *
     * @return SupplierTranslation
     */
    public function setName(?string $name): SupplierTranslation
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string) $this->name;
    }
}
