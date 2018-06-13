<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * StoreEnum
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ShopBundle\Repository\StoreEnumRepository")
 */
class StoreEnum implements UuidEntityInterface
{
    use TimestampableEntity;
    use UuidTrait;
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @var Collection|Store[]
     *
     * @ORM\OneToMany(targetEntity="Store", mappedBy="storeEnum")
     */
    private $stores;

    /**
     * Store constructor.
     */
    public function __construct()
    {
        $this->stores = new ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     * @param string $locale
     *
     * @return StoreEnum
     */
    public function setName(string $name, string $locale)
    {
        /** @var Translation|StoreEnumTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setName($name);

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
        /** @var Translation|StoreEnumTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getName();
    }

    /**
     * @param Store $store
     *
     * @return StoreEnum
     */
    public function addStore(Store $store): StoreEnum
    {
        if (false === $this->stores->contains($store)) {
            $this->stores->add($store);
            $store->setStoreEnum($this);
        }

        return $this;
    }

    /**
     * @return Collection|Store[]
     */
    public function getStores(): Collection
    {
        return $this->stores;
    }
}
