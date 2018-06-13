<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class AttributeGroupEnumRepository
 *
 * @package UmberFirm\Bundle\ProductBundle\Repository
 */
class AttributeGroupEnumRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('attribute_group_enum');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('attribute_group_enum.id', ':search'),
                    $queryBuilder->expr()->like('attribute_group_enum.name', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }
}
