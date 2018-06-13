<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\DataObject;

use UmberFirm\Bundle\CommonBundle\Entity\Branch;
use UmberFirm\Bundle\CommonBundle\Entity\City;
use UmberFirm\Bundle\CommonBundle\Entity\Street;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerAddress;
use UmberFirm\Bundle\OrderBundle\Entity\Promocode;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopCurrency;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDelivery;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPayment;
use UmberFirm\Component\Validator\Constraints\Promocode\PromocodeInterface;
use UmberFirm\Component\Validator\Constraints\ShoppingCart\ShoppingCartInterface;

/**
 * Class PublicOrder
 *
 * @package UmberFirm\Bundle\PublicBundle\DataObject
 */
class PublicOrder implements PromocodeInterface, ShoppingCartInterface
{
    /**
     * @var ShoppingCart
     */
    private $shoppingCart;

    /**
     * @var Promocode
     */
    private $promocode;

    /**
     * @var ShopCurrency
     */
    private $shopCurrency;

    /**
     * @var Shop
     */
    private $shop;

    /**
     * @var Customer
     */
    private $customer;

    /**
     * @var ShopPayment
     */
    private $shopPayment;

    /**
     * @var ShopDelivery
     */
    private $shopDelivery;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $number;

    /**
     * @var string|null
     */
    private $paymentUrl = null;

    /**
     * @var string
     */
    private $firstname;

    /**
     * @var string
     */
    private $lastname;

    /**
     * @var City
     */
    private $city;

    /**
     * @var Street
     */
    private $street;

    /**
     * @var Branch
     */
    private $branch;

    /**
     * @var string
     */
    private $apartment;

    /**
     * @var string
     */
    private $house;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $zip;

    /**
     * @var string
     */
    private $note;

    /**
     * @param null|ShoppingCart $shoppingCart
     *
     * @return PublicOrder
     */
    public function setShoppingCart(?ShoppingCart $shoppingCart): PublicOrder
    {
        $this->shoppingCart = $shoppingCart;

        return $this;
    }

    /**
     * @return null|ShoppingCart
     */
    public function getShoppingCart(): ?ShoppingCart
    {
        return $this->shoppingCart;
    }

    /**
     * @param null|Promocode $promocode
     *
     * @return PublicOrder
     */
    public function setPromocode(?Promocode $promocode): PublicOrder
    {
        $this->promocode = $promocode;

        return $this;
    }

    /**
     * @return null|Promocode
     */
    public function getPromocode(): ?Promocode
    {
        return $this->promocode;
    }

    /**
     * @param null|Shop $shop
     *
     * @return PublicOrder
     */
    public function setShop(?Shop $shop): PublicOrder
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

    /**
     * @param null|Customer $customer
     *
     * @return PublicOrder
     */
    public function setCustomer(?Customer $customer): PublicOrder
    {
        $this->customer = $customer;

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
     * @param null|ShopPayment $shopPayment
     *
     * @return PublicOrder
     */
    public function setShopPayment(?ShopPayment $shopPayment): PublicOrder
    {
        $this->shopPayment = $shopPayment;

        return $this;
    }

    /**
     * @return null|ShopPayment
     */
    public function getShopPayment(): ?ShopPayment
    {
        return $this->shopPayment;
    }

    /**
     * @param string $phone
     *
     * @return PublicOrder
     */
    public function setPhone(?string $phone): PublicOrder
    {
        $this->phone = $phone;

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
     * @param string $email
     *
     * @return PublicOrder
     */
    public function setEmail(?string $email): PublicOrder
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $number
     *
     * @return PublicOrder
     */
    public function setNumber(string $number): PublicOrder
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return (string) $this->number;
    }

    /**
     * @param null|string $paymentUrl
     *
     * @return PublicOrder
     */
    public function setPaymentUrl(?string $paymentUrl)
    {
        $this->paymentUrl = $paymentUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentUrl(): string
    {
        return (string) $this->paymentUrl;
    }

    /**
     * @param null|ShopCurrency $shopCurrency
     *
     * @return PublicOrder
     */
    public function setShopCurrency(?ShopCurrency $shopCurrency): PublicOrder
    {
        $this->shopCurrency = $shopCurrency;

        return $this;
    }

    /**
     * @return null|ShopCurrency
     */
    public function getShopCurrency(): ?ShopCurrency
    {
        return $this->shopCurrency;
    }

    /**
     * @param null|ShopDelivery $shopDelivery
     *
     * @return PublicOrder
     */
    public function setShopDelivery(?ShopDelivery $shopDelivery): PublicOrder
    {
        $this->shopDelivery = $shopDelivery;

        return $this;
    }

    /**
     * @return null|ShopDelivery
     */
    public function getShopDelivery(): ?ShopDelivery
    {
        return $this->shopDelivery;
    }

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
     * @return PublicOrder
     */
    public function setFirstname(?string $firstname): PublicOrder
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
     * @return PublicOrder
     */
    public function setLastname(?string $lastname): PublicOrder
    {
        $this->lastname = $lastname;

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
     * @return PublicOrder
     */
    public function setCity(?City $city): PublicOrder
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
     * @return PublicOrder
     */
    public function setZip(?string $zip): PublicOrder
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
     * @return PublicOrder
     */
    public function setCountry(?string $country): PublicOrder
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @param null|Street $street
     *
     * @return PublicOrder
     */
    public function setStreet(?Street $street): PublicOrder
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
     * @return PublicOrder
     */
    public function setBranch(?Branch $branch): PublicOrder
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
     * @return PublicOrder
     */
    public function setApartment(?string $apartment): PublicOrder
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
     * @return CustomerAddress
     */
    public function createCustomerAddress(): CustomerAddress
    {
        $address = new CustomerAddress();
        $address->setCustomer($this->getCustomer());
        $address->setFirstname($this->getFirstname());
        $address->setLastname($this->getLastname());
        $address->setPhone($this->getPhone());
        $address->setCity($this->getCity());
        $address->setZip($this->getZip());
        $address->setCountry($this->getCountry());
        $address->setDelivery($this->getShopDelivery());
        $address->setStreet($this->getStreet());
        $address->setShop($this->getShop());
        $address->setHouse($this->getHouse());
        $address->setApartment($this->getApartment());

        return $address;
    }

    /**
     * @param null|string $note
     *
     * @return PublicOrder
     */
    public function setNote(?string $note): PublicOrder
    {
        $this->note = $note;

        return $this;
    }

    /**
     * @return string
     */
    public function getNote(): string
    {
        return (string) $this->note;
    }

    /**
     * @param null|string $house
     *
     * @return PublicOrder
     */
    public function setHouse(?string $house): PublicOrder
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
}
