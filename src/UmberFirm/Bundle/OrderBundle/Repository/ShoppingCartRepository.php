<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class ShoppingCartRepository
 *
 * @package UmberFirm\Bundle\OrderBundle\Repository
 */
class ShoppingCartRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('shopping_cart');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->leftJoin('shopping_cart.customer', 'customer')
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('shopping_cart.id', ':search'),
                    $queryBuilder->expr()->like('shopping_cart.amount', ':search'),
                    $queryBuilder->expr()->like('customer.id', ':search'),
                    $queryBuilder->expr()->like('customer.email', ':search'),
                    $queryBuilder->expr()->like('customer.phone', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }

    /**
     * @param Customer $customer
     *
     * @return null|object|ShoppingCart
     */
    public function findOneByCustomer(Customer $customer): ?ShoppingCart
    {
        return $this->findOneBy(
            [
                'customer' => $customer->getId()->toString(),
                'shop' => $customer->getShop()->getId()->toString(),
                'archived' => false,
            ]
        );
    }
}
