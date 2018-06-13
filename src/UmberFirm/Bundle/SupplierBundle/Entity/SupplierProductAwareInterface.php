<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Entity;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\ProductBundle\Entity\Product;

/**
 * Interface SupplierProductAwareInterface
 *
 * @package UmberFirm\Bundle\SupplierBundle\Entity
 */
interface SupplierProductAwareInterface
{
    /**
     * @param Product $product
     *
     * @return SupplierProductAwareInterface
     */
    public function addProduct(Product $product): SupplierProductAwareInterface;

    /**
     * @param Product $product
     *
     * @return SupplierProductAwareInterface
     */
    public function removeProduct(Product $product): SupplierProductAwareInterface;

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection;
}
