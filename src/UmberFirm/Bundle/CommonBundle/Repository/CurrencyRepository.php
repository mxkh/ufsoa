<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class CurrencyRepository
 *
 * @package UmberFirm\Bundle\CommonBundle\Repository
 */
class CurrencyRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('currency');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('currency.name', ':search'),
                    $queryBuilder->expr()->like('currency.code', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }
}
