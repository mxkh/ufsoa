<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Bundle\CommonBundle\Entity\City;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class ShopDeliveryCity
 *
 * @package UmberFirm\Bundle\ShopBundle\Entity
 *
 * @ORM\Table(
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="shop_delivery_city_idx", columns={"shop_id", "city_id", "shop_delivery_id"}
 *          )
 *      }
 * )
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ShopBundle\Repository\ShopDeliveryCityRepository")
 */
class ShopDeliveryCity implements UuidEntityInterface
{
    use UuidTrait;

    /**
     * @var Shop
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\Shop")
     */
    private $shop;

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
    private $shopDelivery;

    /**
     * @param null|Shop $shop
     *
     * @return ShopDeliveryCity
     */
    public function setShop(?Shop $shop): ShopDeliveryCity
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
     * @param null|City $city
     *
     * @return ShopDeliveryCity
     */
    public function setCity(?City $city): ShopDeliveryCity
    {
        $this->city = $city;

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
     * @param null|ShopDelivery $shopDelivery
     *
     * @return ShopDeliveryCity
     */
    public function setShopDelivery(?ShopDelivery $shopDelivery): ShopDeliveryCity
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
}
