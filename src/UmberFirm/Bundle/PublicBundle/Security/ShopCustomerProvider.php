<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Security;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\User\UserInterface;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Repository\CustomerRepository;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class ShopCustomerProvider
 *
 * @package UmberFirm\Bundle\PublicBundle\Security
 */
class ShopCustomerProvider implements ShopCustomerProviderInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var CustomerRepository
     */
    private $repository;

    /**
     * ShopCustomerProvider constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repository = $this->em->getRepository(Customer::class);
    }

    /**
     * {@inheritdoc}
     */
    public function loadCustomerByToken(string $token): ?Customer
    {
        $customer = $this->repository->loadCustomerByToken($token);

        return $customer;
    }

    /**
     * {@inheritdoc}
     */
    public function loadShopByToken(string $token): ?Shop
    {
        $repository = $this->em->getRepository(Shop::class);
        /** @var Shop $shop */
        $shop = $repository->findOneByApiKey($token);

        return $shop;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        throw new \BadMethodCallException();
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
        return Customer::class === $class;
    }
}
