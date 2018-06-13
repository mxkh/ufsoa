<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class LoadShopData
 *
 * @package UmberFirm\Bundle\ShopBundle\DataFixtures\ORM
 */
class LoadShopData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        //HELEN-MARLEN.COM
        $HMShop = new Shop();
        $HMShop->setName('HELEN-MARLEN.COM');
        $HMShop->setApiKey('00000000000000000000000000000000');

        $HMShop->setShopGroup($this->getReference('HM Group'));

        $HMShop->addStore($this->getReference('STORE_Ocean'));
        $HMShop->addStore($this->getReference('STORE_Mandarin'));
        $HMShop->addStore($this->getReference('STORE_Gucci'));
        $HMShop->addStore($this->getReference('STORE_SalvatoreFerragamo'));
        $HMShop->addStore($this->getReference('STORE_HelenMarlenShowroom'));

        //POSH.UA
        $POSHShop = new Shop();
        $POSHShop->setName('POSH.UA');
        $POSHShop->setApiKey('11111111111111111111111111111111');

        $POSHShop->setShopGroup($this->getReference('HM Group'));

        $POSHShop->addStore($this->getReference('STORE_Ocean'));
        $POSHShop->addStore($this->getReference('STORE_Mandarin'));
        $POSHShop->addStore($this->getReference('STORE_Gucci'));
        $POSHShop->addStore($this->getReference('STORE_SalvatoreFerragamo'));

        //MD Shop
        $MDShop = new Shop();
        $MDShop->setName('MD-SHOP.COM');
        $MDShop->setApiKey('22222222222222222222222222222222');

        $MDShop->setShopGroup($this->getReference('MD Group'));

        $manager->persist($HMShop);
        $manager->persist($POSHShop);
        $manager->persist($MDShop);

        $manager->flush();

        $this->setReference('HM SHOP', $HMShop);
        $this->setReference('POSH SHOP', $POSHShop);
        $this->setReference('MD SHOP', $MDShop);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 2;
    }
}
