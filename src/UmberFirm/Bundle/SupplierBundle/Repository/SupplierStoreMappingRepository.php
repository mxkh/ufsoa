<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierStoreMapping;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class SupplierStoreMappingRepository
 *
 * @package UmberFirm\Bundle\SupplierBundle\Repository
 */
class SupplierStoreMappingRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('supplier_store_mapping');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('supplier_store_mapping.id', ':search'),
                    $queryBuilder->expr()->like('supplier_store_mapping.supplierStore', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }

    /**
     * @param Supplier $supplier
     * @param string $supplierStore
     *
     * @return null|object|SupplierStoreMapping
     */
    public function findOneBySupplierStore(Supplier $supplier, string $supplierStore): ?SupplierStoreMapping
    {
        return $this->findOneBy(
            [
                'supplier' => $supplier->getId()->toString(),
                'supplierStore' => $supplierStore,
            ]
        );
    }
}
