<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class ShopDeliveryRepository
 *
 * @package UmberFirm\Bundle\ShopBundle\Repository
 */
class ShopDeliveryRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('shop_delivery');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->leftJoin('shop_delivery.delivery', 'delivery')
            ->leftJoin('delivery.translations', 'translations')
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('shop_delivery.id', ':search'),
                    $queryBuilder->expr()->like('translations.name', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }
}
