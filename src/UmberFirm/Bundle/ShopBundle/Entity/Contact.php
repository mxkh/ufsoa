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
 * Contact
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ShopBundle\Repository\ContactRepository")
 */
class Contact implements UuidEntityInterface
{
    use TimestampableEntity;
    use UuidTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="Value", type="string", length=255)
     */
    private $value;

    /**
     * @var Collection|Store[]
     *
     * @ORM\ManyToMany(targetEntity="Store", mappedBy="contacts")
     */
    private $stores;

    /**
     * @var ContactEnum
     *
     * @ORM\ManyToOne(targetEntity="ContactEnum", inversedBy="contacts")
     */
    private $contactEnum;

    /**
     * Store constructor.
     */
    public function __construct()
    {
        $this->stores = new ArrayCollection();
    }

    /**
     * Set contactEnum
     *
     * @param ContactEnum|null $contactEnum
     *
     * @return Contact
     */
    public function setContactEnum(?ContactEnum $contactEnum): Contact
    {
        $this->contactEnum = $contactEnum;

        if (null !== $contactEnum) {
            $contactEnum->addContact($this);
        }

        return $this;
    }

    /**
     * Get contactEnum
     *
     * @return null|ContactEnum
     */
    public function getContactEnum(): ?ContactEnum
    {
        return $this->contactEnum;
    }

    /**
     * Set value
     *
     * @param null|string $value
     *
     * @return Contact
     */
    public function setValue(?string $value): Contact
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
     * @param Store $store
     *
     * @return Contact
     */
    public function addStore(Store $store): Contact
    {
        if (false === $this->stores->contains($store)) {
            $this->stores->add($store);
            $store->addContact($this);
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
