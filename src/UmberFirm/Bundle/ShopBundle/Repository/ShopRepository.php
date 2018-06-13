<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class ShopRepository
 *
 * @package UmberFirm\Bundle\ShopBundle\Repository
 */
class ShopRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('shop');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('shop.id', ':search'),
                    $queryBuilder->expr()->like('shop.name', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }
}
