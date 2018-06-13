<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Class Store
 *
 * @package UmberFirm\Bundle\ShopBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ShopBundle\Repository\StoreRepository")
 */
class Store implements UuidEntityInterface
{
    use TimestampableEntity;
    use UuidTrait;
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var StoreEnum
     *
     * @ORM\ManyToOne(targetEntity="StoreEnum", inversedBy="stores", cascade={"persist"})
     */
    private $storeEnum;

    /**
     * @var Collection|StoreSocialProfile[]
     *
     * @ORM\ManyToMany(targetEntity="StoreSocialProfile", inversedBy="stores")
     */
    private $storeSocialProfiles;

    /**
     * @var Supplier
     *
     * @ORM\ManyToOne(
     *     targetEntity="UmberFirm\Bundle\SupplierBundle\Entity\Supplier",
     *     inversedBy="stores",
     *     cascade={"persist"}
     * )
     */
    private $supplier;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false, options={"default":true})
     */
    private $isActive = true;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"}, updatable=false)
     *
     * @ORM\Column(length=155, unique=true)
     */
    private $slug;

    /**
     * @var Geolocation
     *
     * @ORM\ManyToOne(targetEntity="Geolocation", inversedBy="stores")
     */
    private $geolocation;

    /**
     * @var Collection|Contact[]
     *
     * @ORM\ManyToMany(targetEntity="Contact", inversedBy="stores")
     */
    private $contacts;

    /**
     * @var Collection|Shop[]
     *
     * @ORM\ManyToMany(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\Shop", inversedBy="stores")
     */
    private $shops;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    private $reference;

    /**
     * Store constructor.
     */
    public function __construct()
    {
        $this->storeSocialProfiles = new ArrayCollection();
        $this->contacts = new ArrayCollection();
        $this->shops = new ArrayCollection();
    }

    /**
     * Set name
     *
     * @param null|string $name
     *
     * @return Store
     */
    public function setName(?string $name): Store
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
        return (string) $this->name;
    }

    /**
     * Set address
     *
     * @param string $address
     * @param string $locale
     *
     * @return Store
     */
    public function setAddress(string $address, string $locale): Store
    {
        /** @var Translation|StoreTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setAddress($address);

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress(): ?string
    {
        /** @var Translation|StoreTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getAddress();
    }

    /**
     * Set schedule
     *
     * @param string $schedule
     * @param string $locale
     *
     * @return Store
     */
    public function setSchedule(string $schedule, string $locale): Store
    {
        /** @var Translation|StoreTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setSchedule($schedule);

        return $this;
    }

    /**
     * Get schedule
     *
     * @return string
     */
    public function getSchedule(): ?string
    {
        /** @var Translation|StoreTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getSchedule();
    }

    /**
     * Set storeEnum
     *
     * @param StoreEnum|null $storeEnum
     *
     * @return Store
     */
    public function setStoreEnum(?StoreEnum $storeEnum): Store
    {
        $this->storeEnum = $storeEnum;

        if (null !== $storeEnum) {
            $storeEnum->addStore($this);
        }

        return $this;
    }

    /**
     * Get storeEnum
     *
     * @return null|StoreEnum
     */
    public function getStoreEnum(): ?StoreEnum
    {
        return $this->storeEnum;
    }

    /**
     * Set storeSocialProfile
     *
     * @param StoreSocialProfile $storeSocialProfile
     *
     * @return Store
     */
    public function addStoreSocialProfile(StoreSocialProfile $storeSocialProfile): Store
    {
        if (false === $this->storeSocialProfiles->contains($storeSocialProfile)) {
            $this->storeSocialProfiles->add($storeSocialProfile);
            $storeSocialProfile->addStore($this);
        }

        return $this;
    }

    /**
     * @param StoreSocialProfile $storeSocialProfile
     *
     * @return Store
     */
    public function removeStoreSocialProfile(StoreSocialProfile $storeSocialProfile): Store
    {
        if (true === $this->storeSocialProfiles->contains($storeSocialProfile)) {
            $this->storeSocialProfiles->removeElement($storeSocialProfile);
            $storeSocialProfile->removeStore($this);
        }

        return $this;
    }

    /**
     * Get storeSocialProfile
     *
     * @return Collection|StoreSocialProfile[]
     */
    public function getStoreSocialProfiles(): Collection
    {
        return $this->storeSocialProfiles;
    }

    /**
     * Get supplier
     *
     * @return null|Supplier
     */
    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    /**
     * Set supplier
     *
     * @param null|Supplier $supplier
     *
     * @return Store
     */
    public function setSupplier(?Supplier $supplier): Store
    {
        $this->supplier = $supplier;
        if (null !== $this->supplier) {
            $supplier->addStore($this);
        }

        return $this;
    }

    /**
     * @return null|Geolocation
     */
    public function getGeolocation(): ?Geolocation
    {
        return $this->geolocation;
    }

    /**
     * Set Geolocation
     *
     * @param null|Geolocation $geolocation
     *
     * @return Store
     */
    public function setGeolocation(?Geolocation $geolocation): Store
    {
        $this->geolocation = $geolocation;
        if (null !== $geolocation) {
            $geolocation->addStore($this);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        /** @var Translation|StoreTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getDescription();
    }

    /**
     * @param string $description
     * @param string $locale
     *
     * @return Store
     */
    public function setDescription(string $description, string $locale): Store
    {
        /** @var Translation|StoreTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setDescription($description);

        return $this;
    }

    /**
     * @return Collection|Contact[]
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    /**
     * @param Contact $contact
     *
     * @return Store
     */
    public function addContact(Contact $contact): Store
    {
        if (false === $this->contacts->contains($contact)) {
            $this->contacts->add($contact);
            $contact->addStore($this);
        }

        return $this;
    }

    /**
     * @param Contact $contact
     *
     * @return Store
     */
    public function removeContact(Contact $contact): Store
    {
        if (true === $this->contacts->contains($contact)) {
            $this->contacts->removeElement($contact);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsActive(): bool
    {
        return (bool) $this->isActive;
    }

    /**
     * @param null|bool $isActive
     *
     * @return Store
     */
    public function setIsActive(?bool $isActive): Store
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection|Shop[]
     */
    public function getShops(): Collection
    {
        return $this->shops;
    }

    /**
     * @param Shop $shop
     *
     * @return Store
     */
    public function addShop(Shop $shop): Store
    {
        if (false === $this->shops->contains($shop)) {
            $this->shops->add($shop);
            $shop->addStore($this);
        }

        return $this;
    }

    /**
     * @param Shop $shop
     *
     * @return Store
     */
    public function removeShop(Shop $shop): Store
    {
        if (true === $this->shops->contains($shop)) {
            $this->shops->removeElement($shop);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return (string) $this->slug;
    }

    /**
     * @param null|string $slug
     *
     * @return Store
     */
    public function setSlug(?string $slug): Store
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @param null|string $reference
     *
     * @return Store
     */
    public function setReference(?string $reference): Store
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return string
     */
    public function getReference(): string
    {
        return (string) $this->reference;
    }
}
