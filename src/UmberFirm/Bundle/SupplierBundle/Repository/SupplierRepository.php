<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class SupplierRepository
 *
 * @package UmberFirm\Bundle\SupplierBundle\Repository
 */
class SupplierRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('supplier');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->leftJoin('supplier.translations', 'translations')
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('supplier.id', ':search'),
                    $queryBuilder->expr()->like('translations.name', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }

    /**
     * @param Product $product
     * @param Supplier $supplier
     *
     * @return null|Supplier
     */
    public function findRelatedOneByProduct(Product $product, Supplier $supplier): ?Supplier
    {
        $query = $this
            ->createQueryBuilder('s')
            ->innerJoin('s.products', 'p')
            ->where('p.id = :product_id')
            ->andWhere('s.id = :supplier_id')
            ->setParameters(
                [
                    'product_id' => $product->getId()->toString(),
                    'supplier_id' => $supplier->getId()->toString(),
                ]
            )->getQuery();

        try {
            return $query->getSingleResult();
        } catch (NoResultException $exception) {
            return null;
        }
    }
}
