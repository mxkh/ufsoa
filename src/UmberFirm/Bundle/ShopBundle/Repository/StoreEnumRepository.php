<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class StoreEnumRepository
 *
 * @package UmberFirm\Bundle\ShopBundle\Repository
 */
class StoreEnumRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('store_enum');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->leftJoin('store_enum.translations', 'translations')
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('store_enum.id', ':search'),
                    $queryBuilder->expr()->like('translations.name', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }
}
