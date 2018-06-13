<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class SocialNetwork
 *
 * @package UmberFirm\Bundle\CustomerBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\CustomerBundle\Repository\SocialNetworkRepository")
 */
class SocialNetwork implements UuidEntityInterface
{
    use UuidTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64)
     */
    private $name;

    /**
     * @var CustomerSocialIdentity
     *
     * @ORM\OneToMany(
     *     targetEntity="UmberFirm\Bundle\CustomerBundle\Entity\CustomerSocialIdentity",
     *     mappedBy="socialNetwork"
     * )
     */
    private $customerSocialIdentities;

    /**
     * SocialNetwork constructor.
     */
    public function __construct()
    {
        $this->customerSocialIdentities = new ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return SocialNetwork
     */
    public function setName(string $name): SocialNetwork
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
        return (string) $this->name;
    }

    /**
     * @return Collection
     */
    public function getCustomerSocialIdentities(): Collection
    {
        return $this->customerSocialIdentities;
    }

    /**
     * @param CustomerSocialIdentity $customerSocialIdentity
     *
     * @return SocialNetwork
     */
    public function addCustomerSocialIdentity(CustomerSocialIdentity $customerSocialIdentity): SocialNetwork
    {
        if (false === $this->customerSocialIdentities->contains($customerSocialIdentity)) {
            $this->customerSocialIdentities->add($customerSocialIdentity);
            $customerSocialIdentity->setSocialNetwork($this);
        }

        return $this;
    }

    /**
     * @param CustomerSocialIdentity $customerSocialIdentity
     *
     * @return SocialNetwork
     */
    public function removeCustomerSocialIdentity(CustomerSocialIdentity $customerSocialIdentity): SocialNetwork
    {
        if (true === $this->customerSocialIdentities->contains($customerSocialIdentity)) {
            $this->customerSocialIdentities->removeElement($customerSocialIdentity);
            $customerSocialIdentity->setSocialNetwork(null);
        }

        return $this;
    }
}
