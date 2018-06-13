<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class SettingsAttributeRepository
 *
 * @package UmberFirm\Bundle\ShopBundle\Repository
 */
class SettingsAttributeRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('settings_attribute');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('settings_attribute.id', ':search'),
                    $queryBuilder->expr()->like('settings_attribute.name', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }
}
