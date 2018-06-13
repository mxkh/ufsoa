<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ShopBundle\Entity\ShopCurrency;

/**
 * Class LoadShopCurrencyData
 *
 * @package UmberFirm\Bundle\ShopBundle\DataFixtures\ORM
 */
class LoadShopCurrencyData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $hmUSD = new ShopCurrency();
        $hmUSD->setShop($this->getReference('HM SHOP'));
        $hmUSD->setCurrency($this->getReference('currency_USD'));
        $hmUSD->setIsDefault(true);

        $hmUAH = new ShopCurrency();
        $hmUAH->setShop($this->getReference('HM SHOP'));
        $hmUAH->setCurrency($this->getReference('currency_UAH'));
        $hmUAH->setIsDefault(false);

        $PoshRUB = new ShopCurrency();
        $PoshRUB->setShop($this->getReference('POSH SHOP'));
        $PoshRUB->setCurrency($this->getReference('currency_RUB'));
        $PoshRUB->setIsDefault(false);

        $PoshEUR = new ShopCurrency();
        $PoshEUR->setShop($this->getReference('POSH SHOP'));
        $PoshEUR->setCurrency($this->getReference('currency_EUR'));
        $PoshEUR->setIsDefault(true);

        $MdGBP = new ShopCurrency();
        $MdGBP->setShop($this->getReference('MD SHOP'));
        $MdGBP->setCurrency($this->getReference('currency_GBP'));
        $MdGBP->setIsDefault(true);

        $MdCNY = new ShopCurrency();
        $MdCNY->setShop($this->getReference('MD SHOP'));
        $MdCNY->setCurrency($this->getReference('currency_CNY'));
        $MdCNY->setIsDefault(false);

        $manager->persist($hmUSD);
        $manager->persist($hmUAH);
        $manager->persist($PoshRUB);
        $manager->persist($PoshEUR);
        $manager->persist($MdGBP);
        $manager->persist($MdCNY);

        $this->addReference('HM:USD', $hmUSD);
        $this->addReference('MD:CNY', $MdCNY);
        $this->addReference('POSH:RUB', $PoshRUB);

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 3;
    }
}
