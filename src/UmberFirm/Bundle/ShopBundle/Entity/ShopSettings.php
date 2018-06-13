<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * ShopSettings
 *
 * @ORM\Table(
 *     name="shop_settings",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="shop_settings_idx", columns={"shop_id", "attribute_id"})}
 * )
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ShopBundle\Repository\ShopSettingsRepository")
 */
class ShopSettings implements UuidEntityInterface
{
    use UuidTrait;

    /**
     * @var Shop
     *
     * @ORM\ManyToOne(targetEntity="Shop", inversedBy="shopSettings")
     * @ORM\JoinColumn(name="shop_id", referencedColumnName="id")
     */
    private $shop;

    /**
     * @var SettingsAttribute
     *
     * @ORM\ManyToOne(targetEntity="SettingsAttribute")
     * @ORM\JoinColumn(name="attribute_id", referencedColumnName="id")
     */
    private $attribute;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;

    /**
     * Set value
     *
     * @param null|string $value
     *
     * @return ShopSettings
     */
    public function setValue(?string $value): ShopSettings
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue(): string
    {
        return (string) $this->value;
    }

    /**
     * @return Shop
     */
    public function getShop(): Shop
    {
        return $this->shop;
    }

    /**
     * @param null|Shop $shop
     *
     * @return ShopSettings
     */
    public function setShop(?Shop $shop): ShopSettings
    {
        $this->shop = $shop;
        $shop->addShopSettings($this);

        return $this;
    }

    /**
     * @return null|SettingsAttribute
     */
    public function getAttribute(): ?SettingsAttribute
    {
        return $this->attribute;
    }

    /**
     * @param null|SettingsAttribute $settingsAttribute
     *
     * @return ShopSettings
     */
    public function setAttribute(?SettingsAttribute $settingsAttribute): ShopSettings
    {
        if (null !== $settingsAttribute) {
            $this->attribute = $settingsAttribute;
        }

        return $this;
    }
}
