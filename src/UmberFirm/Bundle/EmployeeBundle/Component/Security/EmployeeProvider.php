<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\EmployeeBundle\Component\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use UmberFirm\Bundle\EmployeeBundle\Entity\Employee;

/**
 * Class EmployeeProvider
 *
 * @package UmberFirm\Bundle\EmployeeBundle\Component\Security
 */
class EmployeeProvider implements EmployeeProviderInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ShopCustomerProvider constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        $repository = $this->em->getRepository(Employee::class);

        $employee = $repository->findOneBy(['email' => $username]);
        if (null === $employee) {
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username), 0);
        }

        return $employee;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        throw new \BadMethodCallException();
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        throw new \BadMethodCallException();
    }
}
