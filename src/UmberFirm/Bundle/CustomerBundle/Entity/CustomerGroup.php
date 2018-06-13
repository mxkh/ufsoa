<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Class CustomerGroup
 *
 * @package UmberFirm\Bundle\CustomerBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\CustomerBundle\Repository\CustomerGroupRepository")
 */
class CustomerGroup implements UuidEntityInterface, CustomerGroupCustomerAwareInterface
{
    use UuidTrait;
    use TimestampableEntity;
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @var ArrayCollection|Customer[]
     *
     * @ORM\OneToMany(
     *     targetEntity="UmberFirm\Bundle\CustomerBundle\Entity\Customer",
     *     mappedBy="customerGroup",
     *     cascade={"all"}
     * )
     */
    private $customers;

    /**
     * CustomerGroup constructor.
     */
    public function __construct()
    {
        $this->customers = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->translate(null, true)->getName();
    }

    /**
     * Proxy Translation Method
     *
     * @param string $name
     * @param string $locale
     *
     * @return CustomerGroup
     */
    public function setName($name, $locale): CustomerGroup
    {
        $this->translate($locale, false)->setName($name);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    /**
     * {@inheritdoc}
     */
    public function addCustomer(Customer $customer): CustomerGroup
    {
        if (false === $this->customers->contains($customer)) {
            $this->customers->add($customer);
            $customer->setCustomerGroup($this);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeCustomer(Customer $customer): CustomerGroup
    {
        if (true === $this->customers->contains($customer)) {
            $this->customers->removeElement($customer);
            $customer->setCustomerGroup(null);
        }

        return $this;
    }
}
