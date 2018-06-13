<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class CustomerAddressRepository
 *
 * @package UmberFirm\Bundle\CustomerBundle\Repository
 */
class CustomerAddressRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('customer_address');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->leftJoin('customer_address.branch', 'branch')
            ->leftJoin('customer_address.city', 'city')
            ->leftJoin('customer_address.street', 'street')
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('customer_address.id', ':search'),
                    $queryBuilder->expr()->like('street.name', ':search'),
                    $queryBuilder->expr()->like('branch.name', ':search'),
                    $queryBuilder->expr()->like('city.name', ':search'),
                    $queryBuilder->expr()->like('customer_address.country', ':search'),
                    $queryBuilder->expr()->like('customer_address.zip', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }
}
