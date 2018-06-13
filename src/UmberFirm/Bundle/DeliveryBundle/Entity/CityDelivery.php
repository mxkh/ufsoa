<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\DeliveryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Bundle\CommonBundle\Entity\City;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * CityDelivery
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\DeliveryBundle\Repository\CityDeliveryRepository")
 */
class CityDelivery implements UuidEntityInterface
{
    use UuidTrait;

    /**
     * @var DeliveryGroup
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\DeliveryBundle\Entity\DeliveryGroup")
     */
    private $deliveryGroup;

    /**
     * @var City
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\CommonBundle\Entity\City")
     */
    private $city;

    /**
     * @param null|DeliveryGroup $deliveryGroup
     *
     * @return CityDelivery
     */
    public function setDeliveryGroup(?DeliveryGroup $deliveryGroup): CityDelivery
    {
        $this->deliveryGroup = $deliveryGroup;

        return $this;
    }

    /**
     * @return null|DeliveryGroup
     */
    public function getDeliveryGroup(): ?DeliveryGroup
    {
        return $this->deliveryGroup;
    }

    /**
     * @param null|City $city
     *
     * @return CityDelivery
     */
    public function setCity(?City $city): CityDelivery
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
}
