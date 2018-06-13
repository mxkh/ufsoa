<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class ShopDeliveryCityPayment
 *
 * @package UmberFirm\Bundle\ShopBundle\Entity
 *
 * @ORM\Table(
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="shop_delivery_city_payment_idx", columns={"shop_id", "shop_delivery_city_id", "shop_payment_id"}
 *          )
 *      }
 * )
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ShopBundle\Repository\ShopDeliveryCityPaymentRepository")
 */
class ShopDeliveryCityPayment implements UuidEntityInterface
{
    use UuidTrait;

    /**
     * @var Shop
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\Shop")
     */
    private $shop;

    /**
     * @var ShopDeliveryCity
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\ShopDeliveryCity")
     */
    private $shopDeliveryCity;

    /**
     * @var ShopPayment
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\ShopPayment")
     */
    private $shopPayment;

    /**
     * @param null|Shop $shop
     *
     * @return ShopDeliveryCityPayment
     */
    public function setShop(?Shop $shop): ShopDeliveryCityPayment
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
     * @param null|ShopDeliveryCity $shopDeliveryCity
     *
     * @return ShopDeliveryCityPayment
     */
    public function setShopDeliveryCity(?ShopDeliveryCity $shopDeliveryCity): ShopDeliveryCityPayment
    {
        $this->shopDeliveryCity = $shopDeliveryCity;

        return $this;
    }

    /**
     * @return null|ShopDeliveryCity
     */
    public function getShopDeliveryCity(): ?ShopDeliveryCity
    {
        return $this->shopDeliveryCity;
    }

    /**
     * @param null|ShopPayment $shopPayment
     *
     * @return ShopDeliveryCityPayment
     */
    public function setShopPayment(?ShopPayment $shopPayment): ShopDeliveryCityPayment
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
}
