<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\DataObject;

use UmberFirm\Bundle\CommonBundle\Entity\Gender;
use UmberFirm\Bundle\CustomerBundle\Entity\SocialNetwork;

/**
 * Class CustomerSocialDataObject
 *
 * @package UmberFirm\Bundle\CustomerBundle\Entity
 */
class CustomerSocialDataObject implements CustomerSocialDataObjectInterface
{
    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $socialId;

    /**
     * @var SocialNetwork
     */
    private $socialNetwork;

    /**
     * @var null|string
     */
    private $firstname;

    /**
     * @var null|string
     */
    private $lastname;

    /**
     * @var null|\DateTime
     */
    private $birthday;

    /**
     * @var null|Gender
     */
    private $gender;

    /**
     * @return null|string
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param null|string $firstname
     *
     * @return CustomerSocialDataObjectInterface
     */
    public function setFirstname(?string $firstname): CustomerSocialDataObjectInterface
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param null|string $lastname
     *
     * @return CustomerSocialDataObjectInterface
     */
    public function setLastname(?string $lastname): CustomerSocialDataObjectInterface
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
     * @return CustomerSocialDataObjectInterface
     */
    public function setBirthday(\DateTime $birthday = null): CustomerSocialDataObjectInterface
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
     * @return CustomerSocialDataObjectInterface
     */
    public function setGender(?Gender $gender): CustomerSocialDataObjectInterface
    {
        $this->gender = $gender;

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
     * @param string $phone
     *
     * @return CustomerSocialDataObjectInterface
     */
    public function setPhone(?string $phone): CustomerSocialDataObjectInterface
    {
        $this->phone = $phone;

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
     * @param string $email
     *
     * @return CustomerSocialDataObjectInterface
     */
    public function setEmail(?string $email): CustomerSocialDataObjectInterface
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param string $socialId
     *
     * @return CustomerSocialDataObjectInterface
     */
    public function setSocialId(?string $socialId): CustomerSocialDataObjectInterface
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
     * @return CustomerSocialDataObjectInterface
     */
    public function setSocialNetwork(?SocialNetwork $socialNetwork): CustomerSocialDataObjectInterface
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
}
