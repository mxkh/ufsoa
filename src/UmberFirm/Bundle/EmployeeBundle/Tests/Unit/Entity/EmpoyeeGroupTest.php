<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\EmployeeBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\EmployeeBundle\Entity\Employee;
use UmberFirm\Bundle\EmployeeBundle\Entity\EmployeeGroup;

/**
 * Class EmployeeGroupTest
 *
 * @package UmberFirm\Bundle\EmployeeBundle\Tests\Unit
 */
class EmpoyeeGroupTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EmployeeGroup
     */
    private $employeeGroup;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->employeeGroup = new EmployeeGroup();
    }

    public function testDefaultEmployee()
    {
        $employee = new Employee();
        $this->assertInstanceOf(EmployeeGroup::class, $this->employeeGroup->addEmployee($employee));
        $this->assertInstanceOf(Collection::class, $this->employeeGroup->getEmployees());
        $this->assertInstanceOf(Employee::class, $this->employeeGroup->getEmployees()->first());
        $this->assertInstanceOf(EmployeeGroup::class, $this->employeeGroup->removeEmployee($employee));
    }

    public function testName()
    {
        $this->assertInstanceOf(EmployeeGroup::class, $this->employeeGroup->setName('Name', 'en'));
        $this->assertEquals('Name', $this->employeeGroup->getName());
        $this->assertInternalType('string', $this->employeeGroup->getName());
    }
}
