<?php

declare(strict_types=1);

namespace UmberFirm\Component\Pagenator\Repository;

use Doctrine\ORM\QueryBuilder;

/**
 * Interface RepositoryPagenatorInterface
 *
 * @package UmberFirm\Component\Pagenator\Repository
 */
interface RepositoryPagenatorInterface
{
    /**
     * @param null|string $searchQuery
     *
     * @return QueryBuilder
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder;
}
