<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierAttributeMapping;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class SupplierAttributeMappingRepository
 *
 * @package UmberFirm\Bundle\SupplierBundle\Repository
 */
class SupplierAttributeMappingRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('supplier_attribute_mapping');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('supplier_attribute_mapping.id', ':search'),
                    $queryBuilder->expr()->like('supplier_attribute_mapping.supplierAttributeKey', ':search'),
                    $queryBuilder->expr()->like('supplier_attribute_mapping.supplierAttributeValue', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }

    /**
     * @param Supplier $supplier
     * @param string $supplierAttributeKey
     * @param string $supplierAttributeValue
     *
     * @return null|object|SupplierAttributeMapping
     */
    public function findOneBySupplierAttribute(
        Supplier $supplier,
        string $supplierAttributeKey,
        string $supplierAttributeValue
    ): ?SupplierAttributeMapping
    {
        return $this->findOneBy(
            [
                'supplier' => $supplier->getId()->toString(),
                'supplierAttributeKey' => $supplierAttributeKey,
                'supplierAttributeValue' => $supplierAttributeValue,
            ]
        );
    }
}
