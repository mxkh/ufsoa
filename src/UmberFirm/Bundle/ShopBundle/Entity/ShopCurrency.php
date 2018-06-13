<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Bundle\CommonBundle\Entity\Currency;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * ShopCurrency
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="shop_currency_idx", columns={"shop_id", "currency_id"})})
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ShopBundle\Repository\ShopCurrencyRepository")
 */
class ShopCurrency implements UuidEntityInterface, DefaultableInterface
{
    use UuidTrait;

    /**
     * @var Shop
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\Shop", inversedBy="currencies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shop;

    /**
     * @var Currency
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\CommonBundle\Entity\Currency")
     * @ORM\JoinColumn(nullable=false)
     */
    private $currency;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $isDefault = false;

    /**
     * Set shop
     *
     * @param Shop $shop
     *
     * @return ShopCurrency
     */
    public function setShop(Shop $shop): ShopCurrency
    {
        $this->shop = $shop;

        return $this;
    }

    /**
     * Get shop
     *
     * @return Shop
     */
    public function getShop()
    {
        return $this->shop;
    }

    /**
     * @return null|Currency
     */
    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    /**
     * @param Currency $currency
     *
     * @return ShopCurrency
     */
    public function setCurrency(Currency $currency): ShopCurrency
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsDefault(): bool
    {
        return (bool) $this->isDefault;
    }

    /**
     * @param null|bool $isDefault
     *
     * @return ShopCurrency
     */
    public function setIsDefault(?bool $isDefault): ShopCurrency
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    /**
     * @return Collection|ShopCurrency[]
     */
    public function getShopDefaultables(): Collection
    {
        return $this->shop->getCurrencies();
    }
}
