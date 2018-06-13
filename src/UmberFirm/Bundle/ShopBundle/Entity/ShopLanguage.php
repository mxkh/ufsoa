<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Bundle\CommonBundle\Entity\Language;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * ShopLanguage
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="shop_language_idx", columns={"shop_id", "language_id"})})
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ShopBundle\Repository\ShopLanguageRepository")
 */
class ShopLanguage implements UuidEntityInterface, DefaultableInterface
{
    use UuidTrait;

    /**
     * @var Shop
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\Shop", inversedBy="languages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shop;

    /**
     * @var Language
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\CommonBundle\Entity\Language")
     * @ORM\JoinColumn(nullable=false)
     */
    private $language;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default":false})
     * @ORM\JoinColumn(nullable=true)
     */
    private $isDefault = false;

    /**
     * Set shop
     *
     * @param Shop $shop
     *
     * @return ShopLanguage
     */
    public function setShop(Shop $shop): ShopLanguage
    {
        $this->shop = $shop;

        return $this;
    }

    /**
     * Get shop
     *
     * @return Shop
     */
    public function getShop(): Shop
    {
        return $this->shop;
    }

    /**
     * @return null|Language
     */
    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    /**
     * @param Language $language
     *
     * @return ShopLanguage
     */
    public function setLanguage(Language $language): ShopLanguage
    {
        $this->language = $language;

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
     * @param bool $isDefault
     *
     * @return ShopLanguage
     */
    public function setIsDefault(bool $isDefault): ShopLanguage
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    /**
     * @return Collection|ShopLanguage[]
     */
    public function getShopDefaultables(): Collection
    {
        return $this->shop->getLanguages();
    }
}
