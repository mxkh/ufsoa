<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroup;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class AttributeGroupRepository
 *
 * @package UmberFirm\Bundle\ProductBundle\Repository
 */
class AttributeGroupRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('attribute_group');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->leftJoin('attribute_group.translations', 'translations')
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('attribute_group.id', ':search'),
                    $queryBuilder->expr()->like('attribute_group.code', ':search'),
                    $queryBuilder->expr()->like('translations.name', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }

    /**
     * @param array $codes
     *
     * @return AttributeGroup[]
     */
    public function findByCodes(array $codes): array
    {
        return parent::findBy(['code' => $codes]);
    }
}
