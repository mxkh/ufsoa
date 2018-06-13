<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Bundle\DeliveryBundle\Entity\Delivery;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class ShopDelivery
 *
 * @package UmberFirm\Bundle\ShopBundle\Entity
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="shop_delivery_idx", columns={"shop_id", "delivery_id"})})
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ShopBundle\Repository\ShopDeliveryRepository")
 */
class ShopDelivery implements UuidEntityInterface
{
    use UuidTrait;

    /**
     * @var Shop
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\Shop")
     */
    private $shop;

    /**
     * @var Delivery
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\DeliveryBundle\Entity\Delivery")
     */
    private $delivery;

    /**
     * @param null|Shop $shop
     *
     * @return ShopDelivery
     */
    public function setShop(?Shop $shop): ShopDelivery
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
     * @param null|Delivery $delivery
     *
     * @return ShopDelivery
     */
    public function setDelivery(?Delivery $delivery): ShopDelivery
    {
        $this->delivery = $delivery;

        return $this;
    }

    /**
     * @return null|Delivery
     */
    public function getDelivery(): ?Delivery
    {
        return $this->delivery;
    }
}
