<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerAddress;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopCurrency;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDelivery;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPayment;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class Order
 *
 * @package UmberFirm\Bundle\OrderBundle\Entity
 *
 * @see Order is SQL reserved keyword https://mariadb.com/kb/en/mariadb/reserved-words/
 * @ORM\Table(name="orders", indexes={@ORM\Index(name="number_idx", columns={"number"})})
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\OrderBundle\Repository\OrderRepository")
 */
class Order implements UuidEntityInterface, OrderItemAwareInterface
{
    use UuidTrait;
    use TimestampableEntity;

    /**
     * @var ArrayCollection|OrderItem[]
     *
     * @ORM\OneToMany(
     *     targetEntity="UmberFirm\Bundle\OrderBundle\Entity\OrderItem",
     *     mappedBy="order",
     *     cascade={"all"}
     * )
     */
    private $orderItems;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\CustomerBundle\Entity\Customer", inversedBy="orders")
     * @ORM\JoinColumn(nullable=true)
     */
    private $customer;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", precision=6, length=20)
     */
    private $quantity;

    /**
     * @var float
     *
     * @ORM\Column(type="float", precision=6, length=20)
     */
    private $amount;

    /**
     * @var Shop
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\Shop")
     */
    private $shop;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false, options={"default":false})
     */
    private $isFast = false;

    /**
     * @var Promocode|null
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\OrderBundle\Entity\Promocode")
     * @ORM\JoinColumn(nullable=true)
     */
    private $promocode;

    /**
     * @var ShopPayment
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\ShopPayment")
     */
    private $shopPayment;

    /**
     * @var ShopDelivery
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\ShopDelivery")
     */
    private $shopDelivery;

    /**
     * @var CustomerAddress
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\CustomerBundle\Entity\CustomerAddress", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $customerAddress;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true, options={"default":null})
     */
    private $paymentUrl = null;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     */
    private $number;

    /**
     * @var ShopCurrency
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\ShopCurrency")
     */
    private $shopCurrency;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }

    /**
     * @return Customer|null
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     *
     * @return Order
     */
    public function setCustomer(?Customer $customer): Order
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Collection|OrderItem[]
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return Order
     */
    public function addOrderItem(OrderItem $orderItem): Order
    {
        if (false === $this->orderItems->contains($orderItem)) {
            $this->orderItems->add($orderItem);
            $orderItem->setOrder($this);
        }

        return $this;
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return Order
     */
    public function removeOrderItem(OrderItem $orderItem): Order
    {
        if (true === $this->orderItems->contains($orderItem)) {
            $this->orderItems->removeElement($orderItem);
            $orderItem->setOrder(null);
        }

        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     *
     * @return Order
     */
    public function setQuantity(?int $quantity): Order
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     *
     * @return Order
     */
    public function setAmount(?float $amount): Order
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return Shop|null
     */
    public function getShop(): ?Shop
    {
        return $this->shop;
    }

    /**
     * @param Shop|null $shop
     *
     * @return Order
     */
    public function setShop(?Shop $shop): Order
    {
        $this->shop = $shop;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsFast(): bool
    {
        return (bool) $this->isFast;
    }

    /**
     * @param bool|null $isFast
     *
     * @return Order
     */
    public function setIsFast(?bool $isFast): Order
    {
        $this->isFast = $isFast;

        return $this;
    }

    /**
     * @param null|Promocode $promocode
     *
     * @return Order
     */
    public function setPromocode(?Promocode $promocode): Order
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
     * @param null|ShopPayment $shopPayment
     *
     * @return Order
     */
    public function setShopPayment(?ShopPayment $shopPayment): Order
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
     * @param null|string $paymentUrl
     *
     * @return Order
     */
    public function setPaymentUrl(?string $paymentUrl): Order
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
     * @param null|string $number
     *
     * @return Order
     */
    public function setNumber(?string $number): Order
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
     * @param null|ShopCurrency $shopCurrency
     *
     * @return Order
     */
    public function setShopCurrency(?ShopCurrency $shopCurrency): Order
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
     * @return Order
     */
    public function setShopDelivery(?ShopDelivery $shopDelivery): Order
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
     * @param null|CustomerAddress $customerAddress
     *
     * @return Order
     */
    public function setCustomerAddress(?CustomerAddress $customerAddress): Order
    {
        $this->customerAddress = $customerAddress;

        return $this;
    }

    /**
     * @return null|CustomerAddress
     */
    public function getCustomerAddress(): ?CustomerAddress
    {
        return $this->customerAddress;
    }

    /**
     * @param null|string $note
     *
     * @return Order
     */
    public function setNote(?string $note): Order
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
}
