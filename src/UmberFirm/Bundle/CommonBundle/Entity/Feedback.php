<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Class Feedback
 *
 * @package UmberFirm\Bundle\CommonBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\CommonBundle\Repository\FeedbackRepository")
 */
class Feedback implements UuidEntityInterface
{
    use UuidTrait;
    use TimestampableEntity;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=150)
     */
    private $source;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=150)
     */
    private $name;

    /**
     * @var Subject
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\CommonBundle\Entity\Subject")
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $message;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\CustomerBundle\Entity\Customer")
     */
    private $customer;

    /**
     * @var Shop
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\Shop")
     */
    private $shop;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $locale;

    /**
     * @param string $source
     *
     * @return Feedback
     */
    public function setSource(?string $source): Feedback
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return (string) $this->source;
    }

    /**
     * @param string $name
     *
     * @return Feedback
     */
    public function setName(?string $name): Feedback
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
     * @param Subject|null $subject
     *
     * @return Feedback
     */
    public function setSubject(?Subject $subject): Feedback
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return null|Subject
     */
    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    /**
     * @param null|string $email
     *
     * @return Feedback
     */
    public function setEmail(?string $email): Feedback
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return (string) $this->email;
    }

    /**
     * @param null|string $phone
     *
     * @return Feedback
     */
    public function setPhone(?string $phone): Feedback
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return (string) $this->phone;
    }

    /**
     * @param null|string $message
     *
     * @return Feedback
     */
    public function setMessage(?string $message): Feedback
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return (string) $this->message;
    }

    /**
     * @param null|Customer $customer
     *
     * @return Feedback
     */
    public function setCustomer(?Customer $customer): Feedback
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
     * @param null|Shop $shop
     *
     * @return Feedback
     */
    public function setShop(?Shop $shop): Feedback
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
     * @param null|string $reference
     *
     * @return Feedback
     */
    public function setReference(?string $reference): Feedback
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return string
     */
    public function getReference(): ?string
    {
        return $this->reference;
    }

    /**
     * @param string $locale
     *
     * @return Feedback
     */
    public function setLocale(string $locale): Feedback
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }
}
