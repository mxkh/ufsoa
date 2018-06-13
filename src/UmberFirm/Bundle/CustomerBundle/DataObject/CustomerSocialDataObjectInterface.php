<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\DataObject;

use UmberFirm\Bundle\CommonBundle\Entity\Gender;
use UmberFirm\Bundle\CustomerBundle\Entity\SocialNetwork;

/**
 * Interface CustomerSocialDataObjectInterface
 *
 * @package UmberFirm\Bundle\CustomerBundle\Entity
 */
interface CustomerSocialDataObjectInterface
{
    /**
     * @return null|string
     */
    public function getFirstname(): ?string;

    /**
     * @param null|string $firstname
     *
     * @return CustomerSocialDataObjectInterface
     */
    public function setFirstname(?string $firstname): CustomerSocialDataObjectInterface;

    /**
     * @return null|string
     */
    public function getLastname(): ?string;

    /**
     * @param null|string $lastname
     *
     * @return CustomerSocialDataObjectInterface
     */
    public function setLastname(?string $lastname): CustomerSocialDataObjectInterface;

    /**
     * @return null|\DateTime
     */
    public function getBirthday(): ?\DateTime;

    /**
     * @param null|\DateTime $birthday
     *
     * @return CustomerSocialDataObjectInterface
     */
    public function setBirthday(\DateTime $birthday = null): CustomerSocialDataObjectInterface;

    /**
     * @return null|Gender
     */
    public function getGender(): ?Gender;

    /**
     * @param null|Gender $gender
     *
     * @return CustomerSocialDataObjectInterface
     */
    public function setGender(?Gender $gender): CustomerSocialDataObjectInterface;

    /**
     * @return string
     */
    public function getPhone(): string;

    /**
     * @param string $phone
     *
     * @return CustomerSocialDataObjectInterface
     */
    public function setPhone(?string $phone): CustomerSocialDataObjectInterface;

    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @param string $email
     *
     * @return CustomerSocialDataObjectInterface
     */
    public function setEmail(?string $email): CustomerSocialDataObjectInterface;

    /**
     * @param string $socialId
     *
     * @return CustomerSocialDataObjectInterface
     */
    public function setSocialId(?string $socialId): CustomerSocialDataObjectInterface;

    /**
     * @return string
     */
    public function getSocialId(): string;

    /**
     * @param null|SocialNetwork $socialNetwork
     *
     * @return CustomerSocialDataObjectInterface
     */
    public function setSocialNetwork(?SocialNetwork $socialNetwork): CustomerSocialDataObjectInterface;

    /**
     * @return null|SocialNetwork
     */
    public function getSocialNetwork(): ?SocialNetwork;
}
