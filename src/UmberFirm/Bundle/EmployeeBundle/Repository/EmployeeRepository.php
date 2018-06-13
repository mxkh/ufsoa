<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\EmployeeBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class EmployeeRepository
 *
 * @package UmberFirm\Bundle\EmployeeBundle\Repository
 */
class EmployeeRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('employee');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('employee.id', ':search'),
                    $queryBuilder->expr()->like('employee.phone', ':search'),
                    $queryBuilder->expr()->like('employee.email', ':search'),
                    $queryBuilder->expr()->like('employee.name', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }
}
