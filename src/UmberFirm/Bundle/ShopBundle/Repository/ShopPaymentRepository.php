<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class ShopPaymentRepository
 *
 * @package UmberFirm\Bundle\ShopBundle\Repository
 */
class ShopPaymentRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('shop_payment');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->leftJoin('shop_payment.payment', 'payment')
            ->leftJoin('payment.translations', 'translations')
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('shop_payment.id', ':search'),
                    $queryBuilder->expr()->like('payment.code', ':search'),
                    $queryBuilder->expr()->like('translations.name', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }
}
