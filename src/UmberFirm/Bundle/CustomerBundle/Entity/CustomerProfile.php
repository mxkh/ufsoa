<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Bundle\CommonBundle\Entity\Gender;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class CustomerProfile
 *
 * @package UmberFirm\Bundle\CustomerBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\CustomerBundle\Repository\CustomerProfileRepository")
 */
class CustomerProfile implements UuidEntityInterface
{
    use UuidTrait;

    /**
     * @var null|Customer
     *
     * @ORM\OneToOne(
     *     targetEntity="UmberFirm\Bundle\CustomerBundle\Entity\Customer",
     *     inversedBy="profile"
     * )
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    private $lastname;

    /**
     * @var null|\DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthday;

    /**
     * @var null|Gender
     *
     * @ORM\ManyToOne(
     *     targetEntity="UmberFirm\Bundle\CommonBundle\Entity\Gender"
     * )
     */
    private $gender;

    /**
     * @return null|Customer
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * @param null|Customer $customer
     *
     * @return CustomerProfile
     */
    public function setCustomer(?Customer $customer): CustomerProfile
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return (string) $this->firstname;
    }

    /**
     * @param null|string $firstname
     *
     * @return CustomerProfile
     */
    public function setFirstname(?string $firstname): CustomerProfile
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return (string) $this->lastname;
    }

    /**
     * @param null|string $lastname
     *
     * @return CustomerProfile
     */
    public function setLastname(?string $lastname): CustomerProfile
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return null|\DateTime
     */
    public function getBirthday(): ?\DateTime
    {
        return $this->birthday;
    }

    /**
     * @param null|\DateTime $birthday
     *
     * @return CustomerProfile
     */
    public function setBirthday(?\DateTime $birthday): CustomerProfile
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * @return null|Gender
     */
    public function getGender(): ?Gender
    {
        return $this->gender;
    }

    /**
     * @param null|Gender $gender
     *
     * @return CustomerProfile
     */
    public function setGender(?Gender $gender): CustomerProfile
    {
        $this->gender = $gender;

        return $this;
    }
}
