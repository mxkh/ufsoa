<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Bundle\CommonBundle\Entity\City;
use UmberFirm\Bundle\CommonBundle\Entity\Street;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class StreetRepository
 *
 * @package UmberFirm\Bundle\CommonBundle\Repository
 */
class StreetRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('street');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('street.name', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }

    /**
     * @param City $city
     * @param string $query
     * @param int|null $limit
     *
     * @return array|Street[]
     */
    public function findSuggestionsByCity(City $city, string $query, ?int $limit): array
    {
        $queryBuilder = $this->createQueryBuilder('street');
        $queryBuilder
            ->where($queryBuilder->expr()->like('street.name', ':query'))
            ->andWhere($queryBuilder->expr()->eq('street.city', ':city'))
            ->setMaxResults($limit)
            ->setParameters(
                ['query' => '%'.$query.'%', 'city' => $city]
            );

        return $queryBuilder->getQuery()->getResult();
    }
}
