<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\EmployeeBundle\Component\Manager;

use UmberFirm\Bundle\EmployeeBundle\Entity\Employee;

/**
 * Interface LoginManagerInterface
 *
 * @package UmberFirm\Bundle\EmployeeBundle\Component\Manager
 */
interface LoginManagerInterface
{
    /**
     * @param Employee $employee
     */
    public function login(Employee $employee): void;

    /**
     * @param Employee $employee
     * @param $password
     *
     * @return bool
     */
    public function checkEmployeePassword(Employee $employee, $password): bool;

    /**
     * @param string $email
     *
     * @return null|Employee
     */
    public function loadEmployeeByEmail(string $email): ?Employee;
}
