<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Bundle\CommonBundle\Entity\Branch;
use UmberFirm\Bundle\CommonBundle\Entity\City;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class BranchRepository
 *
 * @package UmberFirm\Bundle\CommonBundle\Repository
 */
class BranchRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('branch');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('branch.number', ':search'),
                    $queryBuilder->expr()->like('branch.name', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }

    /**
     * @param City $city
     * @param string $query
     * @param int|null $limit
     *
     * @return array|Branch[]
     */
    public function findSuggestionsByCity(City $city, string $query, ?int $limit): array
    {
        $queryBuilder = $this->createQueryBuilder('branch');
        $queryBuilder
            ->where($queryBuilder->expr()->like('branch.name', ':query'))
            ->andWhere($queryBuilder->expr()->eq('branch.city', ':city'))
            ->setMaxResults($limit)
            ->setParameters(
                ['query' => '%'.$query.'%', 'city' => $city]
            );

        return $queryBuilder->getQuery()->getResult();
    }
}
