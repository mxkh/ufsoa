<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Customer\Manager\Favorite;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\ProductBundle\Entity\Product;

/**
 * Interface FavoriteManagerInterface
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Customer\Manager\Favorite
 */
interface FavoriteManagerInterface
{
    /**
     * @param Product $product
     *
     * @return int
     */
    public function add(Product $product): int;

    /**
     * @param array $products
     *
     * @return int
     */
    public function remove(array $products): int;

    /**
     * @param array $products
     *
     * @return array
     */
    public function get(array $products): array;

    /**
     * @return array
     */
    public function getAll(): array;

    /**
     * @param Customer $customer
     *
     * @return FavoriteManager
     */
    public function setCustomer(Customer $customer): FavoriteManager;
}
