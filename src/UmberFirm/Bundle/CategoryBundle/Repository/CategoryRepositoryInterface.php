<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CategoryBundle\Repository;

use UmberFirm\Bundle\CategoryBundle\Entity\Category;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Interface CategoryRepositoryInterface
 *
 * @package UmberFirm\Bundle\CategoryBundle\Repository
 */
interface CategoryRepositoryInterface
{
    /**
     * @param Shop $shop
     * @param string $id Category id
     *
     * @return Category
     */
    public function getSpecifiedCategoryByShop(Shop $shop, string $id): Category;
}
