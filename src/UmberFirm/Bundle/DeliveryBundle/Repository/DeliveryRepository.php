<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\DeliveryBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class DeliveryRepository
 *
 * @package UmberFirm\Bundle\DeliveryBundle\Repository
 */
class DeliveryRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('delivery');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->leftJoin('delivery.translations', 'translations')
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('delivery.id', ':search'),
                    $queryBuilder->expr()->like('delivery.code', ':search'),
                    $queryBuilder->expr()->like('translations.name', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }
}
