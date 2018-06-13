<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class ImportRepository
 *
 * @package UmberFirm\Bundle\SupplierBundle\Repository
 */
class ImportRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('import');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('import.id', ':search'),
                    $queryBuilder->expr()->like('import.filename', ':search'),
                    $queryBuilder->expr()->like('import.extension', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }
}
