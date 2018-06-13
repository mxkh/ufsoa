<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Geolocation
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ShopBundle\Repository\GeolocationRepository")
 */
class Geolocation implements UuidEntityInterface
{
    use TimestampableEntity;
    use UuidTrait;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float")
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float")
     */
    private $longitude;

    /**
     * @var Collection|Store[]
     *
     * @ORM\OneToMany(targetEntity="Store", mappedBy="geolocation")
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
     * Set latitude
     *
     * @param null|float $latitude
     *
     * @return Geolocation
     */
    public function setLatitude(?float $latitude): Geolocation
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude(): float
    {
        return (float) $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param null|float $longitude
     *
     * @return Geolocation
     */
    public function setLongitude(?float $longitude): Geolocation
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude(): float
    {
        return (float) $this->longitude;
    }

    /**
     * @param Store $store
     *
     * @return Geolocation
     */
    public function addStore(Store $store): Geolocation
    {
        if (false === $this->stores->contains($store)) {
            $this->stores->add($store);
            $store->setGeolocation($this);
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
