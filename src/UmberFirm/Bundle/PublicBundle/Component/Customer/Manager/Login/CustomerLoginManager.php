<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Customer\Manager\Login;

use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\PreAuthenticationJWTUserToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use UmberFirm\Bundle\PublicBundle\Security\PreAuthenticationTokenFactoryInterface;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class CustomerCustomerLoginManager
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Customer\Manager\Login\
 */
final class CustomerLoginManager implements CustomerLoginManagerInterface
{
    const SHOP_IDENTITY_NAME = 'shop';
    const CUSTOMER_IDENTITY_NAME = 'customer';

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
     * LoginManager constructor.
     *
     * @param JWTEncoderInterface $JWTEncoder
     * @param TokenStorageInterface $tokenStorage
     * @param PreAuthenticationTokenFactoryInterface $tokenFactory
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        JWTEncoderInterface $JWTEncoder,
        TokenStorageInterface $tokenStorage,
        PreAuthenticationTokenFactoryInterface $tokenFactory,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager
    ) {
        $this->JWTEncoder = $JWTEncoder;
        $this->tokenStorage = $tokenStorage;
        $this->tokenFactory = $tokenFactory;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Customer $customer
     */
    public function login(Customer $customer): void
    {
        $token = $this->createToken($customer);

        $this->tokenStorage->setToken($token);
    }

    /**
     * @param Customer $customer
     * @param $password
     *
     * @return bool
     */
    public function checkCustomerPassword(Customer $customer, $password): bool
    {
        return $this->passwordEncoder->isPasswordValid($customer, $password);
    }

    /**
     * @param null|string $phone
     * @param null|string $email
     * @param Shop $shop
     *
     * @return null|Customer
     */
    public function loadCustomer(?string $phone, ?string $email, Shop $shop): ?Customer
    {
        return $this->entityManager
            ->getRepository(Customer::class)
            ->findCustomerByPhoneOrEmail($phone, $email, $shop);
    }

    /**
     * @param Customer $customer
     *
     * @return PreAuthenticationJWTUserToken
     */
    private function createToken(Customer $customer): PreAuthenticationJWTUserToken
    {
        $tokenRaw = $this->JWTEncoder->encode(
            [
                //TODO: create TokenObjectFactory
                self::SHOP_IDENTITY_NAME => $customer->getShop()->getApiKey(),
                self::CUSTOMER_IDENTITY_NAME => $customer->getToken()
            ]
        );

        return $this->tokenFactory->createJWTUserToken($tokenRaw);
    }
}
