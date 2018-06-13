<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDeliveryCity;

/**
 * Class LoadShopDeliveryCityData
 *
 * @package UmberFirm\Bundle\ShopBundle\DataFixtures\ORM
 */
class LoadShopDeliveryCityData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $KyivNovaPoshtaWarehouseHM = new ShopDeliveryCity();
        $KyivNovaPoshtaWarehouseHM->setShop($this->getReference('HM SHOP'));
        $KyivNovaPoshtaWarehouseHM->setShopDelivery($this->getReference('ShopDelivery:Nova Poshta:Warehouse:HM'));
        $KyivNovaPoshtaWarehouseHM->setCity($this->getReference('City:Kyiv'));
        $manager->persist($KyivNovaPoshtaWarehouseHM);

        $KyivNovaPoshtaAddressingHM = new ShopDeliveryCity();
        $KyivNovaPoshtaAddressingHM->setShop($this->getReference('HM SHOP'));
        $KyivNovaPoshtaAddressingHM->setShopDelivery($this->getReference('ShopDelivery:Nova Poshta:Addressing:HM'));
        $KyivNovaPoshtaAddressingHM->setCity($this->getReference('City:Kyiv'));
        $manager->persist($KyivNovaPoshtaAddressingHM);

        $OdesaCourierConsultantPosh = new ShopDeliveryCity();
        $OdesaCourierConsultantPosh->setShop($this->getReference('POSH SHOP'));
        $OdesaCourierConsultantPosh->setShopDelivery($this->getReference('ShopDelivery:Courier:Consultant:Posh'));
        $OdesaCourierConsultantPosh->setCity($this->getReference('City:Odesa'));
        $manager->persist($OdesaCourierConsultantPosh);

        $manager->flush();

        $this->setReference('ShopDeliveryCity:Nova Poshta:Warehouse:HM:Kyiv', $KyivNovaPoshtaWarehouseHM);
        $this->setReference('ShopDeliveryCity:Nova Poshta:Addressing:HM:Kyiv', $KyivNovaPoshtaAddressingHM);
        $this->setReference('ShopDeliveryCity:Courier:Consultant:Posh:Odesa', $OdesaCourierConsultantPosh);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 4;
    }
}
