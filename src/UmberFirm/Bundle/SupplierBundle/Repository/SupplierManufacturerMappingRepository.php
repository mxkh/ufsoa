<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierManufacturerMapping;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class SupplierManufacturerMappingRepository
 *
 * @package UmberFirm\Bundle\SupplierBundle\Repository
 */
class SupplierManufacturerMappingRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('supplier_manufacturer_mapping');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('supplier_manufacturer_mapping.id', ':search'),
                    $queryBuilder->expr()->like('supplier_manufacturer_mapping.supplierManufacturer', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }

    /**
     * @param Supplier $supplier
     * @param string $supplierManufacturer
     *
     * @return null|object|SupplierManufacturerMapping
     */
    public function findOneBySupplierManufacturer(
        Supplier $supplier,
        string $supplierManufacturer
    ): ?SupplierManufacturerMapping
    {
        return $this->findOneBy(
            [
                'supplier' => $supplier->getId()->toString(),
                'supplierManufacturer' => $supplierManufacturer,
            ]
        );
    }
}
