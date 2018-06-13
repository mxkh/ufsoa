<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\EmployeeBundle\Tests\Unit\Security;

use Doctrine\ORM\EntityManagerInterface;
use UmberFirm\Bundle\EmployeeBundle\Component\Security\EmployeeProvider;
use UmberFirm\Bundle\EmployeeBundle\Entity\Employee;
use UmberFirm\Bundle\EmployeeBundle\Repository\EmployeeRepository;

/**
 * Class EmployeeProviderTest
 *
 * @package UmberFirm\Bundle\EmployeeBundle\Tests\Unit\Security
 */
class EmployeeProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $em;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
    }

    public function testLoadUserByUsername()
    {
        $repository = $this->createMock(EmployeeRepository::class);
        $repository->expects($this->once())->method('findOneBy')->willReturn(new Employee());
        $this->em->expects($this->once())->method('getRepository')->willReturn($repository);
        $employeeProvider = new EmployeeProvider($this->em);
        $this->assertInstanceOf(Employee::class, $employeeProvider->loadUserByUsername('email'));
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testLoadUserByUsernameNotFoundEmployee()
    {
        $repository = $this->createMock(EmployeeRepository::class);
        $repository->expects($this->once())->method('findOneBy')->willReturn(null);
        $this->em->expects($this->once())->method('getRepository')->willReturn($repository);
        $employeeProvider = new EmployeeProvider($this->em);
        $employeeProvider->loadUserByUsername('email');
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testRefreshUser()
    {
        $employeeProvider = new EmployeeProvider($this->em);
        $employeeProvider->refreshUser(new Employee());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testSupportsClass()
    {
        $employeeProvider = new EmployeeProvider($this->em);
        $employeeProvider->supportsClass(Employee::class);
    }
}
