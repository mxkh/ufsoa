<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * StoreSocialProfile
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ShopBundle\Repository\StoreSocialProfileRepository")
 */
class StoreSocialProfile implements UuidEntityInterface
{
    use TimestampableEntity;
    use UuidTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;

    /**
     * @var SocialProfileEnum
     *
     * @ORM\ManyToOne(targetEntity="SocialProfileEnum", inversedBy="storeSocialProfiles")
     */
    private $socialProfileEnum;

    /**
     * @var Collection|Store[]
     * @ORM\ManyToMany(targetEntity="Store", mappedBy="storeSocialProfiles")
     */
    private $stores;

    /**
     * StoreSocialProfile constructor.
     */
    public function __construct()
    {
        $this->stores = new ArrayCollection();
    }

    /**
     * Set value
     *
     * @param null|string $value
     *
     * @return StoreSocialProfile
     */
    public function setValue(?string $value): StoreSocialProfile
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue(): string
    {
        return (string) $this->value;
    }

    /**
     * @return null|SocialProfileEnum
     */
    public function getSocialProfileEnum(): ?SocialProfileEnum
    {
        return $this->socialProfileEnum;
    }

    /**
     * @param SocialProfileEnum|null $socialProfileEnum
     *
     * @return StoreSocialProfile
     */
    public function setSocialProfileEnum(?SocialProfileEnum $socialProfileEnum): StoreSocialProfile
    {
        $this->socialProfileEnum = $socialProfileEnum;

        if (null !== $socialProfileEnum) {
            $socialProfileEnum->addStoreSocialProfile($this);
        }

        return $this;
    }

    /**
     * @param Store $store
     *
     * @return StoreSocialProfile
     */
    public function addStore(Store $store): StoreSocialProfile
    {
        if (false === $this->stores->contains($store)) {
            $this->stores->add($store);
            $store->addStoreSocialProfile($this);
        }

        return $this;
    }

    /**
     * @param Store $store
     *
     * @return StoreSocialProfile
     */
    public function removeStore(Store $store): StoreSocialProfile
    {
        if (false === $this->stores->contains($store)) {
            $this->stores->removeElement($store);
            $store->removeStoreSocialProfile($this);
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
