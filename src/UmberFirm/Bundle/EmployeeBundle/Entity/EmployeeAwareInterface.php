<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\EmployeeBundle\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * Interface EmployeeAwareInterface
 *
 * @package UmberFirm\Bundle\EmployeeBundle\Entity
 */
interface EmployeeAwareInterface
{
    /**
     * @param Employee $employee
     *
     * @return EmployeeGroup
     */
    public function addEmployee(Employee $employee): EmployeeGroup;

    /**
     * @return Collection|Employee[]
     */
    public function getEmployees(): Collection;

    /**
     * @param Employee $employee
     *
     * @return EmployeeGroup
     */
    public function removeEmployee(Employee $employee): EmployeeGroup;
}
