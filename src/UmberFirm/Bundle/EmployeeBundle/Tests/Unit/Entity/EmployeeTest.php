<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\EmployeeBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\EmployeeBundle\Entity\Employee;

/**
 * Class EmployeeTest
 *
 * @package UmberFirm\Bundle\EmployeeBundle\Tests\Unit
 */
class EmployeeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Employee
     */
    private $employee;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->employee = new Employee();
    }

    public function testName()
    {
        $this->assertInstanceOf(Employee::class, $this->employee->setName('123'));
        $this->assertEquals('123', $this->employee->getName());
        $this->assertInstanceOf(Employee::class, $this->employee->setName(null));
        $this->assertEquals('', $this->employee->getName());
        $this->assertInternalType('string', $this->employee->getName());
    }

    public function testBirthday()
    {
        $this->assertInstanceOf(Employee::class, $this->employee->setBirthday(new \DateTime()));
        $this->assertInstanceOf(\DateTime::class, $this->employee->getBirthday());
        $this->assertInstanceOf(Employee::class, $this->employee->setBirthday(null));
        $this->assertEquals(null, $this->employee->getBirthday());
    }

    public function testEmail()
    {
        $this->assertInstanceOf(Employee::class, $this->employee->setEmail('test@gmail.com'));
        $this->assertEquals('test@gmail.com', $this->employee->getEmail());
        $this->assertInstanceOf(Employee::class, $this->employee->setEmail(null));
        $this->assertEquals('', $this->employee->getEmail());
        $this->assertInternalType('string', $this->employee->getEmail());
    }

    public function testPhone()
    {
        $this->assertInstanceOf(Employee::class, $this->employee->setPhone('123213123'));
        $this->assertEquals('123213123', $this->employee->getPhone());
        $this->assertInstanceOf(Employee::class, $this->employee->setPhone(null));
        $this->assertEquals('', $this->employee->getPhone());
        $this->assertInternalType('string', $this->employee->getPhone());
    }

    public function testPassword()
    {
        $this->assertInstanceOf(Employee::class, $this->employee->setPassword('123123'));
        $this->assertEquals('123123', $this->employee->getPassword());
        $this->assertInstanceOf(Employee::class, $this->employee->setPassword(null));
        $this->assertEquals('', $this->employee->getPassword());
        $this->assertInternalType('string', $this->employee->getPassword());
    }

    public function testRoles()
    {
        $this->assertInternalType('array', $this->employee->getRoles());
    }
}
