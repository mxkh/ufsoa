<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;

/**
 * Interface ProductSupplierAwareInterface
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 */
interface ProductSupplierAwareInterface
{
    /**
     * @param Supplier $supplier
     *
     * @return Product
     */
    public function addSupplier(Supplier $supplier): Product;

    /**
     * @param Supplier $supplier
     *
     * @return Product
     */
    public function removeSupplier(Supplier $supplier): Product;

    /**
     * @return Collection|Product[]
     */
    public function getSuppliers(): Collection;
}
