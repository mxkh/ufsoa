<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class MediaRepository
 *
 * @package UmberFirm\Bundle\MediaBundle\Repository
 */
class MediaRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('media');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('media.id', ':search'),
                    $queryBuilder->expr()->like('media.filename', ':search'),
                    $queryBuilder->expr()->like('media.extension', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }
}
