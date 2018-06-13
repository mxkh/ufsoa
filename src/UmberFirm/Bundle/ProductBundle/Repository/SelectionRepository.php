<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class SelectionRepository
 *
 * @package UmberFirm\Bundle\ProductBundle\Repository
 */
class SelectionRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('selection');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->leftJoin('selection.translations', 'translations')
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('selection.id', ':search'),
                    $queryBuilder->expr()->like('translations.name', ':search'),
                    $queryBuilder->expr()->like('translations.slug', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }
}
