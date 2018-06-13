<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class ShopGroupRepository
 *
 * @package UmberFirm\Bundle\ShopBundle\Repository
 */
class ShopGroupRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('shop_group');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('shop_group.id', ':search'),
                    $queryBuilder->expr()->like('shop_group.name', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }
}
