<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Repository;

use Doctrine\ORM\EntityRepository;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopSettings;

/**
 * Class ShopSettingsRepository
 *
 * @package UmberFirm\Bundle\ShopBundle\Repository
 */
class ShopSettingsRepository extends EntityRepository
{
    /**
     * Find records by shop entity
     *
     * @param Shop $shop
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array
     */
    public function findByShop(Shop $shop, array $orderBy = null, int $limit = null, int $offset = null): array
    {
        return parent::findBy(['shop' => $shop->getId()->toString()], $orderBy, $limit, $offset);
    }

    /**
     * @param Shop $shop
     * @param array $attributeNames
     *
     * @return array
     */
    public function findSetting(Shop $shop, array $attributeNames): array
    {
        $qb = $this->createQueryBuilder('shop_settings');

        $result = (array) $qb
            ->select('attribute.name as key', 'shop_settings.value as value')
            ->where('shop_settings.shop = :shop')
            ->innerJoin('shop_settings.attribute', 'attribute')
            ->andWhere($qb->expr()->in('attribute.name', $attributeNames))
            ->setParameter('shop', $shop->getId()->toString())
            ->getQuery()
            ->getArrayResult();

        return (array) array_reduce(
            $result,
            function ($result, $item) {
                $result[$item['key']] = $item['value'];

                return $result;
            }
        );
    }
}
