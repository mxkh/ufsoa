<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Internal\Hydration\IterableResult;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;

/**
 * Class ProductImportRepository
 *
 * @package UmberFirm\Bundle\ProductBundle\Repository
 */
class ProductImportRepository extends EntityRepository
{
    /**
     * @param Supplier $supplier
     * @param Shop $shop
     *
     * @return IterableResult
     */
    public function productIteratorBySupplier(Supplier $supplier, Shop $shop): IterableResult
    {
        return $this->createQueryBuilder('pi')
            ->where('pi.shop = :shop AND pi.supplier = :supplier')
            ->setParameters(['shop' => $shop, 'supplier' => $supplier,])
            ->getQuery()
            ->iterate();
    }
}
