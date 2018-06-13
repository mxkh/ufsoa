<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\DataObject;

/**
 * Class CustomerPassword
 *
 * @package UmberFirm\Bundle\CustomerBundle\DataObject
 */
class CustomerPassword
{
    /**
     * @var string
     */
    protected $oldPassword;

    /**
     * @var string
     */
    protected $password;

    /**
     * @return string
     */
    public function getOldPassword(): string
    {
        return (string) $this->oldPassword;
    }

    /**
     * @param null|string $oldPassword
     *
     * @return CustomerPassword
     */
    public function setOldPassword(?string $oldPassword): CustomerPassword
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * @param null|string $password
     *
     * @return CustomerPassword
     */
    public function setPassword(?string $password): CustomerPassword
    {
        $this->password = $password;

        return $this;
    }
}