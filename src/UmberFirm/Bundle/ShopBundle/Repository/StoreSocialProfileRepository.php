<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class StoreSocialProfileRepository
 *
 * @package UmberFirm\Bundle\ShopBundle\Repository
 */
class StoreSocialProfileRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('store_social_profile');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('store_social_profile.id', ':search'),
                    $queryBuilder->expr()->like('store_social_profile.value', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }
}
