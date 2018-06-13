<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class ShopPaymentSettings
 *
 * @package UmberFirm\Bundle\PaymentBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ShopBundle\Repository\ShopPaymentSettingsRepository")
 */
class ShopPaymentSettings implements UuidEntityInterface
{
    use UuidTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $publicKey;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $privateKey;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $returnUrl;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $domainName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $merchantAuthType;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $merchantTransactionType;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false, options={"default":false})
     */
    private $testMode = false;

    /**
     * @var ShopPayment
     *
     * @ORM\OneToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\ShopPayment", inversedBy="settings")
     */
    private $shopPayment;

    /**
     * @param null|string $publicKey
     *
     * @return ShopPaymentSettings
     */
    public function setPublicKey(?string $publicKey): ShopPaymentSettings
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getPublicKey(): string
    {
        return (string) $this->publicKey;
    }

    /**
     * @param null|string $privateKey
     *
     * @return ShopPaymentSettings
     */
    public function setPrivateKey(?string $privateKey): ShopPaymentSettings
    {
        $this->privateKey = $privateKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrivateKey(): string
    {
        return (string) $this->privateKey;
    }

    /**
     * @param null|string $returnUrl
     *
     * @return ShopPaymentSettings
     */
    public function setReturnUrl(?string $returnUrl): ShopPaymentSettings
    {
        $this->returnUrl = $returnUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getReturnUrl(): string
    {
        return (string) $this->returnUrl;
    }

    /**
     * @param null|string $merchantAuthType
     *
     * @return ShopPaymentSettings
     */
    public function setMerchantAuthType(?string $merchantAuthType): ShopPaymentSettings
    {
        $this->merchantAuthType = $merchantAuthType;

        return $this;
    }

    /**
     * @return string
     */
    public function getMerchantAuthType(): string
    {
        return (string) $this->merchantAuthType;
    }

    /**
     * @param null|string $merchantTransactionType
     *
     * @return ShopPaymentSettings
     */
    public function setMerchantTransactionType(?string $merchantTransactionType): ShopPaymentSettings
    {
        $this->merchantTransactionType = $merchantTransactionType;

        return $this;
    }

    /**
     * @return string
     */
    public function getMerchantTransactionType(): string
    {
        return (string) $this->merchantTransactionType;
    }

    /**
     * @param bool|null $sandbox
     *
     * @return ShopPaymentSettings
     */
    public function setTestMode(?bool $sandbox): ShopPaymentSettings
    {
        $this->testMode = $sandbox;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTestMode(): bool
    {
        return (bool) $this->testMode;
    }

    /**
     * @param null|ShopPayment $shopPayment
     *
     * @return ShopPaymentSettings
     */
    public function setShopPayment(?ShopPayment $shopPayment): ShopPaymentSettings
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

    /**
     * @param null|string $domainName
     *
     * @return ShopPaymentSettings
     */
    public function setDomainName(?string $domainName): ShopPaymentSettings
    {
        $this->domainName = $domainName;

        return $this;
    }

    /**
     * @return string
     */
    public function getDomainName(): string
    {
        return (string) $this->domainName;
    }
}
