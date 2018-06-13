<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class OrderItem
 *
 * @package UmberFirm\Bundle\OrderBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\OrderBundle\Repository\OrderItemRepository")
 */
class OrderItem implements UuidEntityInterface
{
    use UuidTrait;

    /**
     * @var Order
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\OrderBundle\Entity\Order", inversedBy="orderItems")
     */
    private $order;

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
     * @return Order|null
     */
    public function getOrder(): ?Order
    {
        return $this->order;
    }

    /**
     * @param Order $order
     *
     * @return OrderItem
     */
    public function setOrder(?Order $order): OrderItem
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return (int) $this->quantity;
    }

    /**
     * @param int $quantity
     *
     * @return OrderItem
     */
    public function setQuantity(?int $quantity): OrderItem
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return (float) $this->price;
    }

    /**
     * @param float $price
     *
     * @return OrderItem
     */
    public function setPrice(?float $price): OrderItem
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return (float) $this->amount;
    }

    /**
     * @param float $amount
     *
     * @return OrderItem
     */
    public function setAmount(?float $amount): OrderItem
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return ProductVariant
     */
    public function getProductVariant(): ?ProductVariant
    {
        return $this->productVariant;
    }

    /**
     * @param ProductVariant|null $productVariant
     *
     * @return OrderItem
     */
    public function setProductVariant(?ProductVariant $productVariant): OrderItem
    {
        $this->productVariant = $productVariant;

        return $this;
    }
}
