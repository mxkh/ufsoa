<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\EmployeeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Class EmployeeGroup
 *
 * @package UmberFirm\Bundle\EmployeeBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\EmployeeBundle\Repository\EmployeeGroupRepository")
 */
class EmployeeGroup implements UuidEntityInterface, EmployeeAwareInterface
{
    use UuidTrait;
    use TimestampableEntity;
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @var Collection|Employee[]
     *
     * @ORM\OneToMany(
     *     targetEntity="UmberFirm\Bundle\EmployeeBundle\Entity\Employee",
     *     mappedBy="employeeGroup",
     *     cascade={"all"}
     * )
     */
    private $employees;

    /**
     * EmployeeGroup constructor.
     */
    public function __construct()
    {
        $this->employees = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string) $this->translate(null, true)->getName();
    }

    /**
     * Proxy Translation Method
     *
     * @param string $name
     * @param string $locale
     *
     * @return EmployeeGroup
     */
    public function setName(string $name, string $locale): EmployeeGroup
    {
        $this->translate($locale, false)->setName($name);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    /**
     * {@inheritdoc}
     */
    public function addEmployee(Employee $employee): EmployeeGroup
    {
        if (false === $this->employees->contains($employee)) {
            $this->employees->add($employee);

            $employee->setEmployeeGroup($this);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeEmployee(Employee $employee): EmployeeGroup
    {
        if (true === $this->employees->contains($employee)) {
            $this->employees->removeElement($employee);
            $employee->setEmployeeGroup(null);
        }

        return $this;
    }
}
