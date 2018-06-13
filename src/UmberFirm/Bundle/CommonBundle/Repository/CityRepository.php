<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Bundle\CommonBundle\Entity\City;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class CityRepository
 *
 * @package UmberFirm\Bundle\CommonBundle\Repository
 */
class CityRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('city');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('city.name', ':search'),
                    $queryBuilder->expr()->like('city.id', ':search')
                )
            )
            ->setParameter('search', $searchQuery.'%');
    }

    /**
     * @param string $query
     * @param int $limit
     *
     * @return array|City[]
     */
    public function findSuggestions(string $query, int $limit): array
    {
        $limit = $limit > 0 ?: null;

        $queryBuilder = $this->createQueryBuilder('city');
        $queryBuilder
            ->where($queryBuilder->expr()->like('city.name', ':query'))
            ->setMaxResults($limit)
            ->setParameter('query', '%'.$query.'%');

        return $queryBuilder->getQuery()->getResult();
    }
}
