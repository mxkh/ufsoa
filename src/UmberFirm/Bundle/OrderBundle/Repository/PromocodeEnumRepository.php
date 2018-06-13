<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class PromocodeEnumRepository
 *
 * @package UmberFirm\Bundle\OrderBundle\Repository
 */
class PromocodeEnumRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('promocode_enum');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('promocode_enum.id', ':search'),
                    $queryBuilder->expr()->like('promocode_enum.code', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }
}
