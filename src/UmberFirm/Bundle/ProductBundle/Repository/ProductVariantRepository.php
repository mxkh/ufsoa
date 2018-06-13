<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Repository;

use Doctrine\ORM\EntityRepository;
use UmberFirm\Bundle\ProductBundle\Entity\Department;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;

/**
 * Class ProductVariantRepository
 *
 * @package UmberFirm\Bundle\ProductBundle\Repository
 */
class ProductVariantRepository extends EntityRepository
{
    /**
     * @param ProductVariant $productVariant
     *
     * @return int
     */
    public function updateStock(ProductVariant $productVariant): int
    {
        $departmentRepository = $this->_em->getRepository(Department::class);
        $department = $departmentRepository->findVariantStockByVariantPriority($productVariant);
        $outOfStock = 0 === (int) $department['quantity'] ? 1 : 0;

        return (int) $this->createQueryBuilder('v')
            ->update()
            ->set('v.price', ':price')
            ->set('v.salePrice', ':salePrice')
            ->set('v.article', ':article')
            ->set('v.outOfStock', ':outOfStock')
            ->where('v.id = :id')
            ->setParameters(
                [
                    'id' => $productVariant->getId()->toString(),
                    'price' => (float) $department['price'],
                    'salePrice' => (float) $department['salePrice'],
                    'article' => (string) $department['article'],
                    'outOfStock' => (int) $outOfStock,
                ]
            )
            ->getQuery()
            ->execute();
    }

    /**
     * @param Product $product
     *
     * @return array
     */
    public function findProductStock(Product $product): array
    {
        $qb = $this->createQueryBuilder('pv');

        return $qb
            ->select($qb->expr()->min('pv.price').' price')
            ->addSelect($qb->expr()->max('pv.salePrice').' salePrice')
            ->addSelect($qb->expr()->max('pv.outOfStock').' outOfStock')
            ->addSelect('pv.article article')
            ->where('pv.product = :product')
            ->setParameter('product', $product)
            ->getQuery()
            ->getSingleResult();
    }
}
