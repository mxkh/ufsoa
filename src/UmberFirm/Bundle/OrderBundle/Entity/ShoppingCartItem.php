<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class ShoppingCartItem
 *
 * @package UmberFirm\Bundle\OrderBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\OrderBundle\Repository\ShoppingCartItemRepository")
 */
class ShoppingCartItem implements UuidEntityInterface
{
    use UuidTrait;

    /**
     * @var ShoppingCart
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart", inversedBy="shoppingCartItems")
     */
    private $shoppingCart;

    /**
     * @var ProductVariant
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ProductBundle\Entity\ProductVariant")
     */
    private $productVariant;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @var float
     *
     * @ORM\Column(type="float", precision=6, length=20)
     */
    private $price;

    /**
     * @var float
     *
     * @ORM\Column(type="float", precision=6, length=20)
     */
    private $amount;

    /**
     * @return ShoppingCart|null
     */
    public function getShoppingCart(): ?ShoppingCart
    {
        return $this->shoppingCart;
    }

    /**
     * @param ShoppingCart $shoppingCart
     *
     * @return ShoppingCartItem
     */
    public function setShoppingCart(?ShoppingCart $shoppingCart): ShoppingCartItem
    {
        $this->shoppingCart = $shoppingCart;

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
     * @param int|null $quantity
     *
     * @return ShoppingCartItem
     */
    public function setQuantity(?int $quantity): ShoppingCartItem
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float|null $price
     *
     * @return ShoppingCartItem
     */
    public function setPrice(?float $price): ShoppingCartItem
    {
        $this->price = $price;

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
     * @param float|null $amount
     *
     * @return ShoppingCartItem
     */
    public function setAmount(?float $amount): ShoppingCartItem
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return ProductVariant|null
     */
    public function getProductVariant(): ?ProductVariant
    {
        return $this->productVariant;
    }

    /**
     * @param ProductVariant $productVariant
     *
     * @return ShoppingCartItem
     */
    public function setProductVariant(?ProductVariant $productVariant): ShoppingCartItem
    {
        $this->productVariant = $productVariant;

        return $this;
    }
}
