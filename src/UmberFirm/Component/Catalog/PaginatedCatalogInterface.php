<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog;

use Hateoas\Representation\PaginatedRepresentation;

/**
 * Interface PaginatedCatalogInterface
 *
 * @package UmberFirm\Component\Catalog
 */
interface PaginatedCatalogInterface extends CatalogInterface
{
    /**
     * @param array $categories
     * @param array $attributes
     * @param array $sort
     * @param array $terms
     * @param string $phrase
     *
     * @return PaginatedRepresentation
     */
    public function findPaginated(
        array $categories = [],
        array $attributes = [],
        array $sort = [],
        array $terms = [],
        string $phrase = ''
    ): PaginatedRepresentation;

    /**
     * @param int $currentPage
     *
     * @return PaginatedCatalogInterface
     */
    public function setCurrentPage(int $currentPage): PaginatedCatalogInterface;

    /**
     * @param int $perPage
     *
     * @return PaginatedCatalogInterface
     */
    public function setPerPage(int $perPage): PaginatedCatalogInterface;
}
