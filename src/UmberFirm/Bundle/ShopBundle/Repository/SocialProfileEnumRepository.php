<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class SocialProfileEnumRepository
 *
 * @package UmberFirm\Bundle\ShopBundle\Repository
 */
class SocialProfileEnumRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('social_profile_enum');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->leftJoin('social_profile_enum.translations', 'translations')
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('social_profile_enum.id', ':search'),
                    $queryBuilder->expr()->like('translations.name', ':search'),
                    $queryBuilder->expr()->like('translations.alias', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }
}
