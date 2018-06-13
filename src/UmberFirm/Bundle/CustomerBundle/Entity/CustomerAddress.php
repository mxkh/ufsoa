<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use UmberFirm\Bundle\CommonBundle\Entity\Branch;
use UmberFirm\Bundle\CommonBundle\Entity\City;
use UmberFirm\Bundle\CommonBundle\Entity\Street;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDelivery;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class CustomerAddress
 *
 * @package UmberFirm\Bundle\CustomerBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\CustomerBundle\Repository\CustomerAddressRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class CustomerAddress implements UuidEntityInterface
{
    use UuidTrait;
    use TimestampableEntity;
    use SoftDeleteableEntity;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(
     *     targetEntity="UmberFirm\Bundle\CustomerBundle\Entity\Customer",
     *     inversedBy="customerAddresses"
     * )
     * @ORM\JoinColumn(nullable=true)
     */
    private $customer;

    /**
     * @var Shop
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\Shop")
     */
    private $shop;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    private $phone;

    /**
     * @var City
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\CommonBundle\Entity\City")
     */
    private $city;

    /**
     * @var ShopDelivery
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\ShopDelivery")
     */
    private $delivery;

    /**
     * @var Street
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\CommonBundle\Entity\Street")
     */
    private $street;

    /**
     * @var Branch
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\CommonBundle\Entity\Branch")
     */
    private $branch;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    private $apartment;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    private $house;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $zip;

    /**
     * @return null|string
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param null|string $firstname
     *
     * @return CustomerAddress
     */
    public function setFirstname(?string $firstname): CustomerAddress
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param null|string $lastname
     *
     * @return CustomerAddress
     */
    public function setLastname(?string $lastname): CustomerAddress
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param null|string $phone
     *
     * @return CustomerAddress
     */
    public function setPhone(?string $phone): CustomerAddress
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return null|City
     */
    public function getCity(): ?City
    {
        return $this->city;
    }

    /**
     * @param null|City $city
     *
     * @return CustomerAddress
     */
    public function setCity(?City $city): CustomerAddress
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getZip(): ?string
    {
        return $this->zip;
    }

    /**
     * @param null|string $zip
     *
     * @return CustomerAddress
     */
    public function setZip(?string $zip): CustomerAddress
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param null|string $country
     *
     * @return CustomerAddress
     */
    public function setCountry(?string $country): CustomerAddress
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return null|Customer
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * @param null|Customer $customer
     *
     * @return CustomerAddress
     */
    public function setCustomer(?Customer $customer): CustomerAddress
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @param null|ShopDelivery $delivery
     *
     * @return CustomerAddress
     */
    public function setDelivery(?ShopDelivery $delivery): CustomerAddress
    {
        $this->delivery = $delivery;

        return $this;
    }

    /**
     * @return null|ShopDelivery
     */
    public function getDelivery(): ?ShopDelivery
    {
        return $this->delivery;
    }

    /**
     * @param null|Street $street
     *
     * @return CustomerAddress
     */
    public function setStreet(?Street $street): CustomerAddress
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @return null|Street
     */
    public function getStreet(): ?Street
    {
        return $this->street;
    }

    /**
     * @param null|Branch $branch
     *
     * @return CustomerAddress
     */
    public function setBranch(?Branch $branch): CustomerAddress
    {
        $this->branch = $branch;

        return $this;
    }

    /**
     * @return null|Branch
     */
    public function getBranch(): ?Branch
    {
        return $this->branch;
    }

    /**
     * @param null|string $apartment
     *
     * @return CustomerAddress
     */
    public function setApartment(?string $apartment): CustomerAddress
    {
        $this->apartment = $apartment;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getApartment(): ?string
    {
        return $this->apartment;
    }

    /**
     * @param null|string $house
     *
     * @return CustomerAddress
     */
    public function setHouse(?string $house): CustomerAddress
    {
        $this->house = $house;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getHouse(): ?string
    {
        return $this->house;
    }

    /**
     * @param null|Shop $shop
     *
     * @return CustomerAddress
     */
    public function setShop(?Shop $shop): CustomerAddress
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
