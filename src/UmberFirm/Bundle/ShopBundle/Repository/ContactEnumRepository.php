<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class ContactEnumRepository
 *
 * @package UmberFirm\Bundle\ShopBundle\Repository
 */
class ContactEnumRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('contact_enum');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->leftJoin('contact_enum.translations', 'translations')
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('contact_enum.id', ':search'),
                    $queryBuilder->expr()->like('translations.value', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }
}
