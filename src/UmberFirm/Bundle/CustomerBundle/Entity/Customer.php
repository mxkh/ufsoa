<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use UmberFirm\Bundle\OrderBundle\Entity\Order;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class Customer
 *
 * @package UmberFirm\Bundle\CustomerBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\CustomerBundle\Repository\CustomerRepository")
 */
class Customer implements UuidEntityInterface, CustomerAddressAwareInterface, CustomerOrderAwareInterface, UserInterface
{
    use UuidTrait;
    use TimestampableEntity;

    /**
     * @var CustomerGroup
     *
     * @ORM\ManyToOne(
     *     targetEntity="UmberFirm\Bundle\CustomerBundle\Entity\CustomerGroup",
     *     inversedBy="customers",
     *     cascade={"all"}
     * )
     */
    private $customerGroup;

    /**
     * @var Collection|CustomerSocialIdentity[]
     *
     * @ORM\OneToMany(
     *     targetEntity="UmberFirm\Bundle\CustomerBundle\Entity\CustomerSocialIdentity",
     *     mappedBy="customer"
     * )
     */
    private $customerSocialIdentities;

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
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    private $phone;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    private $password;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $confirmationCode;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $isConfirmed = false;

    /**
     * @var null|CustomerProfile
     *
     * @ORM\OneToOne(
     *     targetEntity="UmberFirm\Bundle\CustomerBundle\Entity\CustomerProfile",
     *     mappedBy="customer",
     *     cascade={"all"}
     * )
     */
    private $profile;

    /**
     * @var Collection|Order[]
     *
     * @ORM\OneToMany(
     *     targetEntity="UmberFirm\Bundle\OrderBundle\Entity\Order",
     *     mappedBy="customer"
     * )
     */
    private $orders;

    /**
     * @var Collection|CustomerAddress[]
     *
     * @ORM\OneToMany(
     *     targetEntity="UmberFirm\Bundle\CustomerBundle\Entity\CustomerAddress",
     *     mappedBy="customer"
     * )
     */
    private $customerAddresses;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=32, nullable=true, unique=true)
     */
    private $token;

    /**
     * @var null|string
     *
     * @ORM\Column(type="string", length=155, nullable=true, unique=true)
     */
    private $resetPasswordCode;

    /**
     * @var null|\DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $resetPasswordCodeExpired;

    /**
     * Customer constructor.
     */
    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->customerAddresses = new ArrayCollection();
        $this->customerSocialIdentities = new ArrayCollection();
    }

    /**
     * @return null|CustomerGroup
     */
    public function getCustomerGroup(): ?CustomerGroup
    {
        return $this->customerGroup;
    }

    /**
     * @param null|CustomerGroup $customerGroup
     *
     * @return Customer
     */
    public function setCustomerGroup(?CustomerGroup $customerGroup): Customer
    {
        $this->customerGroup = $customerGroup;

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
     * @param null|Shop $shop
     *
     * @return Customer
     */
    public function setShop(?Shop $shop): Customer
    {
        $this->shop = $shop;

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
     * @param string|null $email
     *
     * @return Customer
     */
    public function setEmail(string $email = null): Customer
    {
        $this->email = $email;

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
     * @param string $phone
     *
     * @return Customer
     */
    public function setPhone(?string $phone): Customer
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param null|string $password
     *
     * @return Customer
     */
    public function setPassword(?string $password): Customer
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getConfirmationCode(): ?string
    {
        return $this->confirmationCode;
    }

    /**
     * @param null|string $confirmationCode
     *
     * @return Customer
     */
    public function setConfirmationCode(?string $confirmationCode): Customer
    {
        $this->confirmationCode = $confirmationCode;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsConfirmed(): bool
    {
        return $this->isConfirmed;
    }

    /**
     * @param bool $isConfirmed
     *
     * @return Customer
     */
    public function setIsConfirmed(bool $isConfirmed): Customer
    {
        $this->isConfirmed = $isConfirmed;

        return $this;
    }

    /**
     * @return null|CustomerProfile
     */
    public function getProfile(): ?CustomerProfile
    {
        return $this->profile;
    }

    /**
     * @param null|CustomerProfile $profile
     *
     * @return Customer
     */
    public function setProfile(?CustomerProfile $profile): Customer
    {
        $this->profile = $profile;

        if (null !== $this->profile) {
            $this->profile->setCustomer($this);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    /**
     * {@inheritdoc}
     */
    public function addOrder(Order $order): Customer
    {
        if (false === $this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setCustomer($this);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeOrder(Order $order): Customer
    {
        if (true === $this->orders->contains($order)) {
            $this->orders->removeElement($order);
            $order->setCustomer(null);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerAddresses(): Collection
    {
        return $this->customerAddresses;
    }

    /**
     * {@inheritdoc}
     */
    public function addCustomerAddress(CustomerAddress $address): Customer
    {
        if (false === $this->customerAddresses->contains($address)) {
            $this->customerAddresses->add($address);
            $address->setCustomer($this);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeCustomerAddress(CustomerAddress $address): Customer
    {
        if (true === $this->customerAddresses->contains($address)) {
            $this->customerAddresses->removeElement($address);
            $address->setCustomer(null);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return ['ROLE_API_CUSTOMER'];
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }

    /**
     * @return null|string
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param null|string $token
     *
     * @return Customer
     */
    public function setToken(string $token): Customer
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getCustomerSocialIdentities(): Collection
    {
        return $this->customerSocialIdentities;
    }

    /**
     * @return string
     */
    public function getResetPasswordCode(): string
    {
        return (string) $this->resetPasswordCode;
    }

    /**
     * @param null|string $resetPasswordCode
     *
     * @return Customer
     */
    public function setResetPasswordCode(?string $resetPasswordCode): Customer
    {
        $this->resetPasswordCode = $resetPasswordCode;

        return $this;
    }

    /**
     * @return bool
     */
    public function passwordIsEmpty(): bool
    {
        return (bool) empty($this->password);
    }

    /**
     * @return null|\DateTime
     */
    public function getResetPasswordCodeExpired(): ?\DateTime
    {
        return $this->resetPasswordCodeExpired;
    }

    /**
     * @param null|\DateTime $resetPasswordCodeExpired
     *
     * @return Customer
     */
    public function setResetPasswordCodeExpired(?\DateTime $resetPasswordCodeExpired): Customer
    {
        $this->resetPasswordCodeExpired = $resetPasswordCodeExpired;

        return $this;
    }

    /**
     * @return bool
     */
    public function isResetPasswordCodeExpired(): bool
    {
        return new \DateTime() > $this->resetPasswordCodeExpired;
    }
}
