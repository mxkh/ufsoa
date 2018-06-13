<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\EmployeeBundle\Tests\Unit\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\PreAuthenticationJWTUserToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\EmployeeBundle\Component\Manager\LoginManager;
use UmberFirm\Bundle\EmployeeBundle\Component\Manager\LoginManagerInterface;
use UmberFirm\Bundle\EmployeeBundle\Entity\Employee;
use UmberFirm\Bundle\EmployeeBundle\Repository\EmployeeRepository;
use UmberFirm\Bundle\PublicBundle\Security\PreAuthenticationTokenFactoryInterface;

/**
 * Class LoginManagerTest
 *
 * @package UmberFirm\Bundle\EmployeeBundle\Tests\Unit\Manager
 */
class LoginManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var JWTEncoderInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $JWTEncoder;

    /** @var TokenStorageInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $tokenStorage;

    /** @var PreAuthenticationTokenFactoryInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $tokenFactory;

    /** @var UserPasswordEncoderInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $passwordEncoder;

    /** @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $entityManager;

    /** @var string */
    private $identityName;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->JWTEncoder = $this->createMock(JWTEncoderInterface ::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface ::class);
        $this->tokenFactory = $this->createMock(PreAuthenticationTokenFactoryInterface ::class);
        $this->passwordEncoder = $this->createMock(UserPasswordEncoderInterface ::class);
        $this->entityManager = $this->createMock(EntityManagerInterface ::class);
        $this->identityName = 'email';
    }

    public function testLogin()
    {
        /** @var Employee|\PHPUnit_Framework_MockObject_MockObject $employee */
        $employee = $this->createMock(Employee::class);
        $employee->expects($this->once())->method('getEmail')->willReturn('email@gmail.com');
        $this->JWTEncoder->expects($this->once())->method('encode')->willReturn('token');
        $this->tokenStorage->expects($this->once())->method('setToken');
        $this->tokenFactory
            ->expects($this->once())
            ->method('createJWTUserToken')
            ->willReturn(new PreAuthenticationJWTUserToken('token'));

        $loginManager = $this->createLoginManager();
        $loginManager->login($employee);
    }

    public function testCheckEmployeePassword()
    {
        /** @var Employee|\PHPUnit_Framework_MockObject_MockObject $employee */
        $employee = $this->createMock(Employee::class);
        $this->passwordEncoder->expects($this->once())->method('isPasswordValid')->willReturn(true);
        $loginManager = $this->createLoginManager();
        $loginManager->checkEmployeePassword($employee, 'password');
    }

    public function testLoadEmployeeByEmail()
    {
        $repository = $this->createMock(EmployeeRepository::class);
        $repository->expects($this->once())->method('findOneBy')->willReturn(new Employee());
        $this->entityManager->expects($this->once())->method('getRepository')->willReturn($repository);
        $loginManager = $this->createLoginManager();
        $this->assertInstanceOf(Employee::class, $loginManager->loadEmployeeByEmail('email@gmail.com'));
    }

    public function testLoadEmployeeByEmailEmployeeNotFound()
    {
        $repository = $this->createMock(EmployeeRepository::class);
        $repository->expects($this->once())->method('findOneBy')->willReturn(null);
        $this->entityManager->expects($this->once())->method('getRepository')->willReturn($repository);
        $loginManager = $this->createLoginManager();
        $this->assertNull($loginManager->loadEmployeeByEmail('email@gmail.com'));
    }

    /**
     * @expectedException \TypeError
     */
    public function testLoadEmployeeByEmailTypeError()
    {
        $repository = $this->createMock(EmployeeRepository::class);
        $repository->expects($this->once())->method('findOneBy')->willReturn(new Customer());
        $this->entityManager->expects($this->once())->method('getRepository')->willReturn($repository);
        $loginManager = $this->createLoginManager();
        $loginManager->loadEmployeeByEmail('email@gmail.com');
    }

    /**
     * @return LoginManagerInterface
     */
    private function createLoginManager(): LoginManagerInterface
    {
        return new LoginManager(
            $this->JWTEncoder,
            $this->tokenStorage,
            $this->tokenFactory,
            $this->passwordEncoder,
            $this->entityManager,
            $this->identityName
        );
    }
}
