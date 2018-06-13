<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class OrderRepository
 *
 * @package UmberFirm\Bundle\OrderBundle\Repository
 */
class OrderRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('orders');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->leftJoin('orders.customer', 'customer')
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('orders.id', ':search'),
                    $queryBuilder->expr()->like('orders.number', ':search'),
                    $queryBuilder->expr()->like('customer.id', ':search'),
                    $queryBuilder->expr()->like('customer.email', ':search'),
                    $queryBuilder->expr()->like('customer.phone', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }

    /**
     * @param Customer $customer
     *
     * @return QueryBuilder
     */
    public function createCustomerOrderQuery(Customer $customer): QueryBuilder
    {
        $queryBuilder = $this
            ->createQueryBuilder('orders')
            ->where('orders.customer = :customer_id')
            ->setParameters(
                [
                    'customer_id' => $customer->getId()->toString(),
                ]
            );

        return $queryBuilder;
    }
}
