<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\EmployeeBundle\Component\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\PreAuthenticationJWTUserToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use UmberFirm\Bundle\EmployeeBundle\Entity\Employee;
use UmberFirm\Bundle\PublicBundle\Security\PreAuthenticationTokenFactoryInterface;

/**
 * Class LoginManager
 *
 * @package UmberFirm\Bundle\EmployeeBundle\Component\Manager
 */
final class LoginManager implements LoginManagerInterface
{
    /**
     * @var JWTEncoderInterface
     */
    private $JWTEncoder;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var PreAuthenticationTokenFactoryInterface
     */
    private $tokenFactory;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var string
     */
    private $identityName;

    /**
     * LoginManager constructor.
     *
     * @param JWTEncoderInterface $JWTEncoder
     * @param TokenStorageInterface $tokenStorage
     * @param PreAuthenticationTokenFactoryInterface $tokenFactory
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface $entityManager
     * @param string $identityName
     */
    public function __construct(
        JWTEncoderInterface $JWTEncoder,
        TokenStorageInterface $tokenStorage,
        PreAuthenticationTokenFactoryInterface $tokenFactory,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager,
        string $identityName
    ) {
        $this->JWTEncoder = $JWTEncoder;
        $this->tokenStorage = $tokenStorage;
        $this->tokenFactory = $tokenFactory;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
        $this->identityName = $identityName;
    }

    /**
     * {@inheritdoc}
     */
    public function login(Employee $employee): void
    {
        $token = $this->createToken($employee);

        $this->tokenStorage->setToken($token);
    }

    /**
     * {@inheritdoc}
     */
    public function checkEmployeePassword(Employee $employee, $password): bool
    {
        return $this->passwordEncoder->isPasswordValid($employee, $password);
    }

    /**
     * {@inheritdoc}
     */
    public function loadEmployeeByEmail(string $email): ?Employee
    {
        return $this->entityManager->getRepository(Employee::class)->findOneBy([$this->identityName => $email]);
    }

    /**
     * @param Employee $employee
     *
     * @return PreAuthenticationJWTUserToken
     */
    private function createToken(Employee $employee): PreAuthenticationJWTUserToken
    {
        $tokenRaw = $this->JWTEncoder->encode([$this->identityName => $employee->getEmail()]);

        return $this->tokenFactory->createJWTUserToken($tokenRaw);
    }
}
