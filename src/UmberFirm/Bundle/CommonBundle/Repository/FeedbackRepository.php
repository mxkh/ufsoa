<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class FeedbackRepository
 *
 * @package UmberFirm\Bundle\CommonBundle\Repository
 */
class FeedbackRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('feedback');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('feedback.id', ':search'),
                    $queryBuilder->expr()->like('feedback.email', ':search'),
                    $queryBuilder->expr()->like('feedback.phone', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }

    /**
     * @param Customer $customer
     *
     * @return QueryBuilder
     */
    public function createCustomerFeedbackQuery(Customer $customer): QueryBuilder
    {
        $queryBuilder = $this
            ->createQueryBuilder('feedback')
            ->where('feedback.customer = :customer')
            ->setParameter('customer', $customer->getId()->toString());

        return $queryBuilder;
    }

    /**
     * @param Shop $shop
     *
     * @return QueryBuilder
     */
    public function createShopFeedbackQuery(Shop $shop): QueryBuilder
    {
        $queryBuilder = $this
            ->createQueryBuilder('feedback')
            ->where('feedback.shop = :shop')
            ->setParameter('shop', $shop->getId()->toString());

        return $queryBuilder;
    }
}
