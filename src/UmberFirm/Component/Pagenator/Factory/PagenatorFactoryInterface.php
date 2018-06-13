<?php

declare(strict_types=1);

namespace UmberFirm\Component\Pagenator\Factory;

use Doctrine\ORM\QueryBuilder;
use Hateoas\Representation\PaginatedRepresentation;

/**
 * Interface PagenatorFactoryInterface
 *
 * @package UmberFirm\Component\Pagenator\Factory
 */
interface PagenatorFactoryInterface
{
    /**
     * @param int $limit
     * @param int $currentPage
     * @param array $parameters
     *
     * @return PaginatedRepresentation
     */
    public function getRepresentation(int $limit = 10, int $currentPage = 1, array $parameters = []): PaginatedRepresentation;

    /**
     * @param string $entityClass
     * @param null|string $searchQuery
     *
     * @return PagenatorFactoryInterface
     */
    public function searchByQuery(string $entityClass, ?string $searchQuery): PagenatorFactoryInterface;

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder;
}
