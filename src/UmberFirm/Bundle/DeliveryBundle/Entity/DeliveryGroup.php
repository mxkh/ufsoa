<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\DeliveryBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;

/**
 * Class DeliveryGroup
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\DeliveryBundle\Repository\DeliveryGroupRepository")
 */
class DeliveryGroup implements UuidEntityInterface
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
     * @var Collection|Delivery[]
     *
     * @ORM\OneToMany(
     *     targetEntity="UmberFirm\Bundle\DeliveryBundle\Entity\Delivery",
     *     mappedBy="group",
     *     cascade={"remove"}
     * )
     */
    private $deliveries;

    /**
     * DeliveryGroup constructor.
     */
    public function __construct()
    {
        $this->deliveries = new ArrayCollection();
    }

    /**
     * @param null|string $code
     *
     * @return DeliveryGroup
     */
    public function setCode(?string $code): DeliveryGroup
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return (string) $this->code;
    }

    /**
     * Proxy method for translations
     *
     * @return string
     */
    public function getName(): string
    {
        /** @var Translation|DeliveryGroupTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getName();
    }

    /**
     * Proxy Translation Method
     *
     * @param string $name
     * @param string $locale
     *
     * @return DeliveryGroup
     */
    public function setName(string $name, string $locale): DeliveryGroup
    {
        /** @var Translation|DeliveryGroupTranslation $translation */
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
        /** @var Translation|DeliveryGroupTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getDescription();
    }

    /**
     * Proxy Translation Method
     *
     * @param string $description
     * @param string $locale
     *
     * @return DeliveryGroup
     */
    public function setDescription(string $description, string $locale): DeliveryGroup
    {
        /** @var Translation|DeliveryGroupTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setDescription($description);

        return $this;
    }
}
