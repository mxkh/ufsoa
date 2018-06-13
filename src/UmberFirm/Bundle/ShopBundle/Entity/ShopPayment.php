<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Bundle\PaymentBundle\Entity\Payment;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class ShopPayment
 *
 * @package UmberFirm\Bundle\ShopBundle\Entity
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="shop_payment_idx", columns={"shop_id", "payment_id"})})
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ShopBundle\Repository\ShopPaymentRepository")
 */
class ShopPayment implements UuidEntityInterface
{
    use UuidTrait;

    /**
     * @var Shop
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\Shop")
     */
    private $shop;

    /**
     * @var Payment
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\PaymentBundle\Entity\Payment")
     */
    private $payment;

    /**
     * @var ShopPaymentSettings
     *
     * @ORM\OneToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\ShopPaymentSettings", mappedBy="shopPayment", cascade={"all"})
     */
    private $settings;

    /**
     * @param null|Shop $shop
     *
     * @return ShopPayment
     */
    public function setShop(?Shop $shop): ShopPayment
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
     * @param null|Payment $payment
     *
     * @return ShopPayment
     */
    public function setPayment(?Payment $payment): ShopPayment
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * @return null|Payment
     */
    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    /**
     * @param ShopPaymentSettings $settings
     *
     * @return ShopPayment
     */
    public function setSettings(ShopPaymentSettings $settings): ShopPayment
    {
        $settings->setShopPayment($this);
        $this->settings = $settings;

        return $this;
    }

    /**
     * @return null|ShopPaymentSettings
     */
    public function getSettings(): ?ShopPaymentSettings
    {
        return $this->settings;
    }
}
