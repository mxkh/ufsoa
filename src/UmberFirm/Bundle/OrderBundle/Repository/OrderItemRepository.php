<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Bundle\OrderBundle\Entity\Order;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class OrderItemRepository
 *
 * @package UmberFirm\Bundle\OrderBundle\Repository
 */
class OrderItemRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('order_item');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('order_item.id', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }

    /**
     * @param Order $order
     *
     * @return QueryBuilder
     */
    public function createOrderItemQuery(Order $order): QueryBuilder
    {
        $queryBuilder = $this
            ->createQueryBuilder('orderItem')
            ->where('orderItem.order = :order_id')
            ->setParameters(
                [
                    'order_id' => $order->getId()->toString(),
                ]
            );

        return $queryBuilder;
    }
}
