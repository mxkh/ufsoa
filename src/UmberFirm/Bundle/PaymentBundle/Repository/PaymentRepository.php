<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PaymentBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class PaymentRepository
 *
 * @package UmberFirm\Bundle\PaymentBundle\Repository
 */
class PaymentRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('payment');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->leftJoin('payment.translations', 'translations')
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('payment.id', ':search'),
                    $queryBuilder->expr()->like('payment.code', ':search'),
                    $queryBuilder->expr()->like('translations.name', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }
}
