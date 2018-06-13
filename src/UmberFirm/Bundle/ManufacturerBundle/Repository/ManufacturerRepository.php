<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ManufacturerBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class ManufacturerRepository
 *
 * @package UmberFirm\Bundle\ManufacturerBundle\Repository
 */
class ManufacturerRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('manufacturer');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('manufacturer.id', ':search'),
                    $queryBuilder->expr()->like('manufacturer.name', ':search'),
                    $queryBuilder->expr()->like('manufacturer.slug', ':search'),
                    $queryBuilder->expr()->like('manufacturer.website', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }

    /**
     * @param Shop $shop
     * @param int $offset
     * @param int $limit
     *
     * @return Manufacturer[]|array
     */
    public function findByShop(Shop $shop, int $offset, ?int $limit): array
    {
        $query = $this
            ->createQueryBuilder('manufacturer')
            ->innerJoin('manufacturer.shops', 'shops')
            ->where('shops.id = :shop_id')
            ->setParameters(
                [
                    'shop_id' => $shop->getId()->toString(),
                ]
            )
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        return $query->getResult();
    }
}
