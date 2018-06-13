<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

use Doctrine\ORM\Query;
use UmberFirm\Bundle\ProductBundle\Entity\Department;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;

/**
 * Class DepartmentRepository
 *
 * @package UmberFirm\Bundle\ProductBundle\Repository
 */
class DepartmentRepository extends EntityRepository
{
    /**
     * Reset quantity to all of departments of supplier what were not given to the method
     *
     * @param array|Department[] $departments
     * @param Supplier $supplier
     * @param Shop $shop
     *
     * @return int
     */
    public function resetQuantityNotIn(array $departments, Supplier $supplier, Shop $shop): int
    {
        $qb = $this->createQueryBuilder('d');
        $IdsNotInExpr = $qb->expr()->notIn('d.id', ':departmentIds');

        return (int) $qb->update()
            ->set('d.quantity', 0)
            ->where($IdsNotInExpr)
            ->andWhere('d.supplier = :supplier')
            ->andWhere('d.shop = :shop')
            ->setParameters(
                [
                    'departmentIds' => $departments,
                    'supplier' => $supplier,
                    'shop' => $shop,
                ]
            )
            ->getQuery()
            ->execute();
    }

    /**
     * Find Product Variants by priority within variant in departments
     *
     * @param ProductVariant $productVariant
     *
     * @return array
     */
    public function findVariantStockByVariantPriority(ProductVariant $productVariant): array
    {
        try {
            $maxPriority = (int) $this->queryMaxPriorityWithinVariant($productVariant)->getSingleScalarResult();
        } catch (NoResultException $exception) {
            $maxPriority = 0;
        }

        return $this
            ->queryVariantStockByPriority($productVariant, $maxPriority)
            ->getSingleResult();
    }

    /**
     * Find Product Variants by priority within product in departments
     *
     * @param ProductVariant $productVariant
     *
     * @return array|null
     */
    public function findVariantStockByProductPriority(ProductVariant $productVariant): array
    {
        try {
            $maxPriority = (int) $this
                ->queryMaxPriorityWithinProduct($productVariant->getProduct())
                ->getSingleScalarResult();
        } catch (NoResultException $exception) {
            $maxPriority = 0;
        }

        return $this
            ->queryVariantStockByPriority($productVariant, $maxPriority)
            ->getSingleResult();
    }

    /**
     * Query to find max value of priority in Departments within ProductVariant
     * Use to update stocks by priority within ProductVariant
     *
     * @param ProductVariant $productVariant
     *
     * @return Query
     */
    public function queryMaxPriorityWithinVariant(ProductVariant $productVariant): Query
    {
        $qb = $this->createQueryBuilder('d');

        return $qb
            ->select($qb->expr()->max('d.priority'))
            ->where('d.productVariant = :productVariant')
            ->setParameter('productVariant', $productVariant)
            ->groupBy('d.productVariant')
            ->getQuery();
    }

    /**
     * Query to find max value of priority in Departments within Product
     * Use to update stocks by priority within Product
     *
     * @param Product $product
     *
     * @return Query
     */
    public function queryMaxPriorityWithinProduct(Product $product): Query
    {
        $qb = $this->createQueryBuilder('d');

        return $qb
            ->select($qb->expr()->max('d.priority'))
            ->innerJoin('d.productVariant', 'pv')
            ->where('pv.product = :product')
            ->setParameter('product', $product)
            ->groupBy('pv.product')
            ->getQuery();
    }

    /**
     * @param ProductVariant $productVariant
     * @param int $priority
     *
     * @return Query
     */
    public function queryVariantStockByPriority(ProductVariant $productVariant, int $priority): Query
    {
        $qb = $this->createQueryBuilder('d');

        return $this->createQueryBuilder('d')
            ->select($qb->expr()->max('d.price').' price')
            ->addSelect($qb->expr()->max('d.salePrice').' salePrice')
            ->addSelect('SUM(d.quantity) quantity')
            ->addSelect('d.article article')
            ->where('d.priority = :priority')
            ->andWhere('d.productVariant = :productVariant')
            ->setParameters(
                [
                    'productVariant' => $productVariant,
                    'priority' => $priority,
                ]
            )
            ->getQuery();
    }
    
    /**
     * @param string $barcode
     * 
     * @return Department|null
     */
    public function findByBarcode(string $barcode): ?Department
    {
        return $this->createQueryBuilder('department')
            ->where('department.ean13 = :code')
            ->orWhere('department.upc = :code')
            ->setParameters(
                [
                    'code' => $barcode,
                ]
            )
            ->getQuery()
            ->getOneOrNullResult();
    }
}
