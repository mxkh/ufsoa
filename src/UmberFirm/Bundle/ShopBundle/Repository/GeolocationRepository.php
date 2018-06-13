<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class GeolocationRepository
 *
 * @package UmberFirm\Bundle\ShopBundle\Repository
 */
class GeolocationRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('geolocation');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('geolocation.id', ':search'),
                    $queryBuilder->expr()->like('geolocation.latitude', ':search'),
                    $queryBuilder->expr()->like('geolocation.longitude', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }
}
