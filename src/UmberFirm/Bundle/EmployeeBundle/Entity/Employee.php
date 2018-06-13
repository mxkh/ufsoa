<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\EmployeeBundle\Entity;

use UmberFirm\Component\Security\Core\Employee\EmployeeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class Employee
 *
 * @package UmberFirm\Bundle\EmployeeBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\EmployeeBundle\Repository\EmployeeRepository")
 */
class Employee implements UuidEntityInterface, EmployeeInterface
{
    use UuidTrait;
    use TimestampableEntity;

    /**
     * @var null|EmployeeGroup
     *
     * @ORM\ManyToOne(
     *     targetEntity="UmberFirm\Bundle\EmployeeBundle\Entity\EmployeeGroup",
     *     inversedBy="employees"
     * )
     */
    private $employeeGroup;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155)
     */
    private $phone;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthday;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @return EmployeeGroup|null
     */
    public function getEmployeeGroup(): ?EmployeeGroup
    {
        return $this->employeeGroup;
    }

    /**
     * @param null|EmployeeGroup $employeeGroup
     *
     * @return Employee
     */
    public function setEmployeeGroup(?EmployeeGroup $employeeGroup): Employee
    {
        $this->employeeGroup = $employeeGroup;

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
     * @param string $name
     *
     * @return $this
     */
    public function setName(?string $name)
    {
        $this->name = $name;

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
     * @return Employee
     */
    public function setEmail(?string $email): Employee
    {
        $this->email = $email;

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
     * @return Employee
     */
    public function setPhone(?string $phone): Employee
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getBirthday(): ?\DateTime
    {
        return $this->birthday;
    }

    /**
     * @param \DateTime $birthday
     *
     * @return Employee
     */
    public function setBirthday(?\DateTime $birthday): Employee
    {
        $this->birthday = $birthday;

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
     * @param string $password
     *
     * @return Employee
     */
    public function setPassword(?string $password): Employee
    {
        $this->password = $password;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles(): array
    {
        return ['ROLE_CORE_API'];
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }
}
