<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;

/**
 * Class Subject
 *
 * @package UmberFirm\Bundle\CommonBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\CommonBundle\Repository\SubjectRepository")
 */
class Subject implements UuidEntityInterface
{
    use UuidTrait;
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @var Shop
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\Shop")
     */
    private $shop;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false, options={"default":true})
     */
    private $isActive = true;

    /**
     * Proxy method for translations
     *
     * @param string $locale
     *
     * @return string
     */
    public function getName(string $locale = null): string
    {
        /** @var Translation|SubjectTranslation $translation */
        $translation = $this->translate($locale, true);

        return $translation->getName();
    }

    /**
     * Proxy Translation Method
     *
     * @param string $name
     * @param string $locale
     *
     * @return Subject
     */
    public function setName(string $name, string $locale): Subject
    {
        /** @var Translation|SubjectTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setName($name);

        return $this;
    }

    /**
     * @param bool|null $isActive
     *
     * @return Subject
     */
    public function setIsActive(?bool $isActive): Subject
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool) $this->isActive;
    }

    /**
     * @param null|Shop $shop
     *
     * @return Subject
     */
    public function setShop(?Shop $shop): Subject
    {
        $this->shop = $shop;

        return $this;
    }

    /**
     * @return null|Shop
     */
    public function getShop(): ?Shop
    {
        return $this->shop;
    }
}
