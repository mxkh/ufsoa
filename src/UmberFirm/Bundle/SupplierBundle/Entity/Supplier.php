<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\Store;
use UmberFirm\Component\Doctrine\Entity\Gedmo\Sortable\SortableTrait;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Class Supplier
 *
 * @package UmberFirm\Bundle\SupplierBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\SupplierBundle\Repository\SupplierRepository")
 */
class Supplier implements
    UserInterface,
    \Serializable,
    UuidEntityInterface
{
    use TimestampableEntity;
    use UuidTrait;
    use ORMBehaviors\Translatable\Translatable;
    use SortableTrait;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean", options={"default":true})
     */
    private $isActive;

    /**
     * @var ArrayCollection|Shop[]
     *
     * @ORM\ManyToMany(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\Shop", inversedBy="suppliers")
     */
    private $shops;

    /**
     * @var ArrayCollection|Store[]
     *
     * @ORM\OneToMany(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\Store", mappedBy="supplier")
     */
    private $stores;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * Supplier constructor.
     */
    public function __construct()
    {
        $this->shops = new ArrayCollection();
        $this->stores = new ArrayCollection();
    }

    /**
     * @param null|string $name
     * @param string $locale
     *
     * @return Supplier
     */
    public function setName(?string $name, string $locale): Supplier
    {
        /** @var Supplier|SupplierTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setName($name);

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        /** @var Supplier|SupplierTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getName();
    }

    /**
     * Set isActive.
     *
     * @param null|bool $isActive
     *
     * @return Supplier
     */
    public function setIsActive(?bool $isActive): Supplier
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive.
     *
     * @return bool
     */
    public function getIsActive(): bool
    {
        return (bool)$this->isActive;
    }

    /**
     * Set description.
     *
     * @param null|string $description
     * @param string $locale
     *
     * @return Supplier
     */
    public function setDescription(?string $description, string $locale): Supplier
    {
        /** @var Supplier|SupplierTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setDescription($description);

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        /** @var Supplier|SupplierTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getDescription();
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
     * @return Supplier
     */
    public function addShop(Shop $shop): Supplier
    {
        if (false === $this->shops->contains($shop)) {
            $this->shops->add($shop);
            $shop->addSupplier($this);
        }

        return $this;
    }

    /**
     * @param Shop $shop
     *
     * @return Supplier
     */
    public function removeShop(Shop $shop): Supplier
    {
        if (true === $this->shops->contains($shop)) {
            $this->shops->removeElement($shop);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return (string)$this->username;
    }

    /**
     * @param null|string $username
     *
     * @return Supplier
     */
    public function setUsername(?string $username): Supplier
    {
        $this->username = $username;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return (string)$this->password;
    }

    /**
     * @param null|string $password
     *
     * @return Supplier
     */
    public function setPassword(?string $password): Supplier
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return null
     */
    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return ['ROLE_SUPPLIER'];
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize(
            [
                $this->id,
                $this->username,
                $this->password,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list($this->id, $this->username, $this->password) = unserialize($serialized);
    }

    /**
     * @return Collection|Store[]
     */
    public function getStores(): Collection
    {
        return $this->stores;
    }

    /**
     * @param Store $store
     *
     * @return Supplier
     */
    public function addStore(Store $store): Supplier
    {
        if (false === $this->stores->contains($store)) {
            $this->stores->add($store);
            $store->setSupplier($this);
        }

        return $this;
    }

    /**
     * @param Store $store
     *
     * @return Supplier
     */
    public function removeStore(Store $store): Supplier
    {
        if (true === $this->stores->contains($store)) {
            $this->stores->removeElement($store);
        }

        return $this;
    }
}
