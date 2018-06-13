<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDelivery;

/**
 * Class LoadShopDeliveryData
 *
 * @package UmberFirm\Bundle\ShopBundle\DataFixtures\ORM
 */
class LoadShopDeliveryData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $novaPoshtaWarehouseHM = new ShopDelivery();
        $novaPoshtaWarehouseHM->setShop($this->getReference('HM SHOP'));
        $novaPoshtaWarehouseHM->setDelivery($this->getReference('DeliveryGroup:Nova Poshta Warehouse'));
        $manager->persist($novaPoshtaWarehouseHM);

        $novaPoshtaAddressingHM = new ShopDelivery();
        $novaPoshtaAddressingHM->setShop($this->getReference('HM SHOP'));
        $novaPoshtaAddressingHM->setDelivery($this->getReference('DeliveryGroup:Nova Poshta Addressing'));
        $manager->persist($novaPoshtaAddressingHM);

        $courierConsultantPosh = new ShopDelivery();
        $courierConsultantPosh->setShop($this->getReference('POSH SHOP'));
        $courierConsultantPosh->setDelivery($this->getReference('Delivery:Courier Consultant'));
        $manager->persist($courierConsultantPosh);

        $manager->flush();

        $this->setReference('ShopDelivery:Nova Poshta:Warehouse:HM', $novaPoshtaWarehouseHM);
        $this->setReference('ShopDelivery:Nova Poshta:Addressing:HM', $novaPoshtaAddressingHM);
        $this->setReference('ShopDelivery:Courier:Consultant:Posh', $courierConsultantPosh);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 3;
    }
}
