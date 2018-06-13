<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Internal\Hydration\IterableResult;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;

/**
 * Class VariantImportRepository
 *
 * @package UmberFirm\Bundle\ProductBundle\Repository
 */
class VariantImportRepository extends EntityRepository
{
    /**
     * @param Supplier $supplier
     * @param Shop $shop
     *
     * @return IterableResult
     */
    public function variantIteratorBySupplier(Supplier $supplier, Shop $shop): IterableResult
    {
        return $this->createQueryBuilder('vi')
            ->where('vi.shop = :shop AND vi.supplier = :supplier')
            ->setParameters(['shop' => $shop, 'supplier' => $supplier,])
            ->getQuery()
            ->iterate();
    }
}
