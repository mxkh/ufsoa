<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CategoryBundle\Repository;

use Gedmo\Tree\RepositoryInterface;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Interface CategoryNestedTreeRepository
 *
 * @package UmberFirm\Bundle\CategoryBundle\Repository
 */
interface CategoryNestedTreeRepositoryInterface extends RepositoryInterface
{
    /**
     * Finds all root nodes in specified shop.
     *
     * @param Shop $shop
     * @param null|string $sortByField
     * @param string $direction
     *
     * @return array
     */
    public function getRootNodesByShop(Shop $shop, ?string $sortByField = null, string $direction = 'asc'): array;
}
