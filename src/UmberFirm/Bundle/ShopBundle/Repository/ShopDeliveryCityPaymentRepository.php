<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class ShopDeliveryCityPaymentRepository
 *
 * @package UmberFirm\Bundle\ShopBundle\Repository
 */
class ShopDeliveryCityPaymentRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        return $this->createQueryBuilder('shop_delivery_city_payment');
    }
}
