<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Bundle\OrderBundle\Entity\Promocode;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class PromocodeRepository
 *
 * @package UmberFirm\Bundle\OrderBundle\Repository
 */
class PromocodeRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('promocode');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('promocode.id', ':search'),
                    $queryBuilder->expr()->like('promocode.code', ':search'),
                    $queryBuilder->expr()->like('promocode.value', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }

    /**
     * @param string $code
     *
     * @return null|object|Promocode
     */
    public function findOneByCode(string $code): ?Promocode
    {
        return $this->findOneBy(['code' => $code]);
    }
}
