<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class CustomerSocialIdentity
 *
 * @package UmberFirm\Bundle\CustomerBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\CustomerBundle\Repository\CustomerSocialIdentityRepository")
 */
class CustomerSocialIdentity implements UuidEntityInterface
{
    use UuidTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text")
     */
    private $socialId;

    /**
     * @var SocialNetwork
     *
     * @ORM\ManyToOne(
     *     targetEntity="UmberFirm\Bundle\CustomerBundle\Entity\SocialNetwork",
     *     inversedBy="customerSocialIdentities"
     * )
     */
    private $socialNetwork;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(
     *     targetEntity="UmberFirm\Bundle\CustomerBundle\Entity\Customer",
     *     inversedBy="customerSocialIdentities"
     * )
     */
    private $customer;

    /**
     * @param string $socialId
     *
     * @return CustomerSocialIdentity
     */
    public function setSocialId(string $socialId): CustomerSocialIdentity
    {
        $this->socialId = $socialId;

        return $this;
    }

    /**
     * @return string
     */
    public function getSocialId(): string
    {
        return (string) $this->socialId;
    }

    /**
     * @param null|SocialNetwork $socialNetwork
     *
     * @return CustomerSocialIdentity
     */
    public function setSocialNetwork(?SocialNetwork $socialNetwork): CustomerSocialIdentity
    {
        $this->socialNetwork = $socialNetwork;

        return $this;
    }

    /**
     * @return null|SocialNetwork
     */
    public function getSocialNetwork(): ?SocialNetwork
    {
        return $this->socialNetwork;
    }

    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     *
     * @return CustomerSocialIdentity
     */
    public function setCustomer(Customer $customer): CustomerSocialIdentity
    {
        $this->customer = $customer;

        return $this;
    }
}
