<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class ShoppingCart
 *
 * @package UmberFirm\Bundle\OrderBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\OrderBundle\Repository\ShoppingCartRepository")
 */
class ShoppingCart implements UuidEntityInterface, ShoppingCartItemAwareInterface
{
    use UuidTrait;
    use TimestampableEntity;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\CustomerBundle\Entity\Customer")
     * @ORM\JoinColumn(nullable=true)
     */
    private $customer;

    /**
     * @var ArrayCollection|ShoppingCartItem[]
     *
     * @ORM\OneToMany(targetEntity="UmberFirm\Bundle\OrderBundle\Entity\ShoppingCartItem", mappedBy="shoppingCart")
     */
    private $shoppingCartItems;

    /**
     * @var Shop
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\Shop")
     */
    private $shop;

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
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false, options={"default":false})
     */
    private $archived = false;

    /**
     * ShoppingCart constructor.
     */
    public function __construct()
    {
        $this->shoppingCartItems = new ArrayCollection();
    }

    /**
     * @return Collection|ShoppingCartItem[]
     */
    public function getShoppingCartItems(): Collection
    {
        return $this->shoppingCartItems;
    }

    /**
     * @param ShoppingCartItem $shoppingCartItem
     *
     * @return ShoppingCart
     */
    public function addShoppingCartItem(ShoppingCartItem $shoppingCartItem): ShoppingCart
    {
        if (false === $this->shoppingCartItems->contains($shoppingCartItem)) {
            $this->shoppingCartItems->add($shoppingCartItem);
            $shoppingCartItem->setShoppingCart($this);
        }

        return $this;
    }

    /**
     * @param ShoppingCartItem $shoppingCartItem
     *
     * @return ShoppingCart
     */
    public function removeShoppingCartItem(ShoppingCartItem $shoppingCartItem): ShoppingCart
    {
        if (true === $this->shoppingCartItems->contains($shoppingCartItem)) {
            $this->shoppingCartItems->removeElement($shoppingCartItem);
            $shoppingCartItem->setShoppingCart(null);
        }

        return $this;
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
     * @return ShoppingCart
     */
    public function setCustomer(?Customer $customer): ShoppingCart
    {
        $this->customer = $customer;

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
     * @return ShoppingCart
     */
    public function setQuantity(?int $quantity): ShoppingCart
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
     * @return ShoppingCart
     */
    public function setAmount(?float $amount): ShoppingCart
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
     * @param Shop $shop
     *
     * @return ShoppingCart
     */
    public function setShop(?Shop $shop): ShoppingCart
    {
        $this->shop = $shop;

        return $this;
    }

    /**
     * @param bool|null $archived
     *
     * @return ShoppingCart
     */
    public function setArchived(?bool $archived): ShoppingCart
    {
        $this->archived = $archived;

        return $this;
    }

    /**
     * @return bool
     */
    public function isArchived(): bool
    {
        return (bool) $this->archived;
    }
}
