<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class CustomerRepository
 *
 * @package UmberFirm\Bundle\CustomerBundle\Repository
 */
class CustomerRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('customer');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('customer.id', ':search'),
                    $queryBuilder->expr()->like('customer.phone', ':search'),
                    $queryBuilder->expr()->like('customer.email', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }

    /**
     * @param string $token
     *
     * @return null|object|Customer
     */
    public function loadCustomerByToken(string $token): ?Customer
    {
        return $this->findOneBy(['token' => $token]);
    }

    /**
     * @param string $phone
     * @param Shop $shop
     *
     * @return null|object|Customer
     */
    public function loadCustomerByPhone(string $phone, Shop $shop): ?Customer
    {
        return $this->findOneBy(['phone' => $phone, 'shop' => $shop]);
    }

    /**
     * @param null|string $phone
     * @param null|string $email
     * @param Shop $shop
     *
     * @return null|object|Customer
     */
    public function findCustomerByPhoneOrEmail(?string $phone, ?string $email, Shop $shop): ?Customer
    {
        $customer = null;
        if (null !== $phone) {
            $customer = $this->findOneBy(['phone' => $phone, 'shop' => $shop]);
        }

        if (null === $customer && null !== $email) {
            $customer = $this->findOneBy(['email' => $email, 'shop' => $shop]);
        }

        return $customer;
    }

    /**
     * @param null|string $code
     * @param Shop $shop
     *
     * @return null|object|Customer
     */
    public function findOneByResetPasswordCode(?string $code, Shop $shop): ?Customer
    {
        $customer = $this->findOneBy(['resetPasswordCode' => $code, 'shop' => $shop]);

        return $customer;
    }

    /**
     * @param null|string $code
     * @param null|string $customerId
     * @param Shop $shop
     *
     * @return null|object|Customer
     */
    public function findOneByConfirmationCode(?string $code, ?string $customerId, Shop $shop): ?Customer
    {
        $customer = $this->findOneBy(
            [
                'id' => $customerId,
                'confirmationCode' => $code,
                'shop' => $shop,
                'isConfirmed' => false,
            ]
        );

        return $customer;
    }
}
