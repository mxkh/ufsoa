<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class CustomerGroupRepository
 *
 * @package UmberFirm\Bundle\CustomerBundle\Repository
 */
class CustomerGroupRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('customer_group');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->leftJoin('customer_group.translations', 'translations')
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('customer_group.id', ':search'),
                    $queryBuilder->expr()->like('translations.phone', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }
}
