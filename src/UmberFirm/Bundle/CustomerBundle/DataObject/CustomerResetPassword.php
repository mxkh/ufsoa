<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\DataObject;

/**
 * Class CustomerResetPassword
 *
 * @package UmberFirm\Bundle\CustomerBundle\DataObject
 */
class CustomerResetPassword
{
    /**
     * @var string
     */
    protected $resetPasswordCode;

    /**
     * @var string
     */
    protected $password;

    /**
     * @return string
     */
    public function getResetPasswordCode(): string
    {
        return (string) $this->resetPasswordCode;
    }

    /**
     * @param null|string $resetPasswordCode
     *
     * @return CustomerResetPassword
     */
    public function setResetPasswordCode(?string $resetPasswordCode): CustomerResetPassword
    {
        $this->resetPasswordCode = $resetPasswordCode;

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
     * @return CustomerResetPassword
     */
    public function setPassword(?string $password): CustomerResetPassword
    {
        $this->password = $password;

        return $this;
    }
}
