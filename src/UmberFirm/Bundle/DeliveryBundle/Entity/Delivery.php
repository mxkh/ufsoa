<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\DeliveryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;

/**
 * Class Delivery
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\DeliveryBundle\Repository\DeliveryRepository")
 */
class Delivery implements UuidEntityInterface
{
    use UuidTrait;
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155, unique=true)
     */
    private $code;

    /**
     * @var DeliveryGroup
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\DeliveryBundle\Entity\DeliveryGroup", inversedBy="deliveries")
     */
    private $group;

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
     * @return Delivery
     */
    public function setCode(?string $code): Delivery
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @param null|DeliveryGroup $group
     *
     * @return Delivery
     */
    public function setGroup(?DeliveryGroup $group): Delivery
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return null|DeliveryGroup
     */
    public function getGroup(): ?DeliveryGroup
    {
        return $this->group;
    }

    /**
     * Proxy method for translations
     *
     * @return string
     */
    public function getName(): string
    {
        /** @var Translation|DeliveryTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getName();
    }

    /**
     * Proxy Translation Method
     *
     * @param string $name
     * @param string $locale
     *
     * @return Delivery
     */
    public function setName(string $name, string $locale): Delivery
    {
        /** @var Translation|DeliveryTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setName($name);

        return $this;
    }

    /**
     * Proxy method for translations
     *
     * @return string
     */
    public function getDescription(): string
    {
        /** @var Translation|DeliveryTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getDescription();
    }

    /**
     * Proxy Translation Method
     *
     * @param string $description
     * @param string $locale
     *
     * @return Delivery
     */
    public function setDescription(string $description, string $locale): Delivery
    {
        /** @var Translation|DeliveryTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setDescription($description);

        return $this;
    }
}
