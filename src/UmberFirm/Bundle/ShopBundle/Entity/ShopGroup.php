<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class ShopGroup
 *
 * @package UmberFirm\Bundle\ShopBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ShopBundle\Repository\ShopGroupRepository")
 */
class ShopGroup implements UuidEntityInterface
{
    use TimestampableEntity;
    use UuidTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=128, nullable=false)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Shop", mappedBy="shopGroup")
     */
    private $shops;

    /**
     * ShopGroup constructor.
     */
    public function __construct()
    {
        $this->shops = new ArrayCollection();
    }

    /**
     * @param null|string $name
     *
     * @return ShopGroup
     */
    public function setName(?string $name): ShopGroup
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string) $this->name;
    }

    /**
     * @param Shop $shop
     *
     * @return ShopGroup
     */
    public function addShop(Shop $shop): ShopGroup
    {
        if (false === $this->shops->contains($shop)) {
            $this->shops->add($shop);
            $shop->setShopGroup($this);
        }

        return $this;
    }

    /**
     * @return Collection|Shop[]
     */
    public function getShops(): Collection
    {
        return $this->shops;
    }
}
