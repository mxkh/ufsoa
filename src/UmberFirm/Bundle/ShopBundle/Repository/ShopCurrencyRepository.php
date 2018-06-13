<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Repository;

use Doctrine\ORM\EntityRepository;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopCurrency;

/**
 * ShopCurrencyRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ShopCurrencyRepository extends EntityRepository
{
    /**
     * @param Shop $shop
     *
     * @return array|ShopCurrency[]
     */
    public function findCurrenciesByShop(Shop $shop): array
    {
        return $this->findBy(
            ['shop' => $shop->getId()->toString()],
            ['isDefault' => 'DESC']
        );
    }
}
