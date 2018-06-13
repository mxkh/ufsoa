<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CategoryBundle\Repository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class CategoryRepository
 *
 * @package UmberFirm\Bundle\CategoryBundle\Repository
 */
class CategoryRepository extends NestedTreeRepository implements CategoryNestedTreeRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRootNodesByShop(Shop $shop, ?string $sortByField = null, string $direction = 'asc'): array
    {
        $qb = $this->getRootNodesQueryBuilder($sortByField, $direction);
        $qb->andWhere($qb->expr()->eq('node.shop', ':shop'))
            ->setParameter('shop', $shop);

        return $qb->getQuery()->getResult();
    }
}
