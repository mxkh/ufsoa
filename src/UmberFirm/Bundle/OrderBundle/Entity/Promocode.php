<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Class Promocode
 *
 * @package UmberFirm\Bundle\OrderBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\OrderBundle\Repository\PromocodeRepository")
 */
class Promocode implements UuidEntityInterface
{
    use UuidTrait;
    use TimestampableEntity;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64, nullable=false, unique=true)
     */
    private $code;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $value;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\CustomerBundle\Entity\Customer")
     * @ORM\JoinColumn(nullable=true)
     */
    private $customer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $finish;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false, options={"default":true})
     */
    private $isReusable = true;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true, options={"default":0})
     */
    private $limiting = 0;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false, options={"default":0})
     */
    private $used = 0;

    /**
     * @var PromocodeEnum
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\OrderBundle\Entity\PromocodeEnum")
     * @ORM\JoinColumn(nullable=false)
     */
    private $promocodeEnum;

    /**
     * @param null|string $code
     *
     * @return Promocode
     */
    public function setCode(?string $code): Promocode
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return (string) $this->code;
    }

    /**
     * @param int|null $value
     *
     * @return Promocode
     */
    public function setValue(?int $value): Promocode
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return (int) $this->value;
    }

    /**
     * @param null|Customer $customer
     *
     * @return Promocode
     */
    public function setCustomer(?Customer $customer): Promocode
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return null|Customer
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * @param \DateTime|null $start
     *
     * @return Promocode
     */
    public function setStart(?\DateTime $start): Promocode
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getStart(): ?\DateTime
    {
        return $this->start;
    }

    /**
     * @param \DateTime|null $finish
     *
     * @return Promocode
     */
    public function setFinish(?\DateTime $finish): Promocode
    {
        $this->finish = $finish;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getFinish(): ?\DateTime
    {
        return $this->finish;
    }

    /**
     * @param bool|null $isReusable
     *
     * @return Promocode
     */
    public function setIsReusable(?bool $isReusable): Promocode
    {
        $this->isReusable = $isReusable;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsReusable(): bool
    {
        return (bool) $this->isReusable;
    }

    /**
     * @param int|null $limiting
     *
     * @return Promocode
     */
    public function setLimiting(?int $limiting): Promocode
    {
        $this->limiting = (int) $limiting;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimiting(): int
    {
        return (int) $this->limiting;
    }

    /**
     * @return Promocode
     */
    public function used(): Promocode
    {
        ++$this->used;

        return $this;
    }

    /**
     * @return int
     */
    public function getUsed(): int
    {
        return (int) $this->used;
    }

    /**
     * @param null|PromocodeEnum $promocodeEnum
     *
     * @return Promocode
     */
    public function setPromocodeEnum(?PromocodeEnum $promocodeEnum): Promocode
    {
        $this->promocodeEnum = $promocodeEnum;

        return $this;
    }

    /**
     * @return null|PromocodeEnum
     */
    public function getPromocodeEnum(): ?PromocodeEnum
    {
        return $this->promocodeEnum;
    }
}
