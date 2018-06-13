<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDeliveryCityPayment;

/**
 * Class LoadShopDeliveryCityPaymentData
 *
 * @package UmberFirm\Bundle\ShopBundle\DataFixtures\ORM
 */
class LoadShopDeliveryCityPaymentData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $KyivNovaPoshtaWarehouseHMWayForPay = new ShopDeliveryCityPayment();
        $KyivNovaPoshtaWarehouseHMWayForPay->setShop($this->getReference('HM SHOP'));
        $KyivNovaPoshtaWarehouseHMWayForPay->setShopDeliveryCity($this->getReference('ShopDeliveryCity:Nova Poshta:Warehouse:HM:Kyiv'));
        $KyivNovaPoshtaWarehouseHMWayForPay->setShopPayment($this->getReference('Payment:HM:WayForPay'));
        $manager->persist($KyivNovaPoshtaWarehouseHMWayForPay);

        $KyivNovaPoshtaWarehouseHMCash = new ShopDeliveryCityPayment();
        $KyivNovaPoshtaWarehouseHMCash->setShop($this->getReference('HM SHOP'));
        $KyivNovaPoshtaWarehouseHMCash->setShopDeliveryCity($this->getReference('ShopDeliveryCity:Nova Poshta:Warehouse:HM:Kyiv'));
        $KyivNovaPoshtaWarehouseHMCash->setShopPayment($this->getReference('Payment:HM:Cash'));
        $manager->persist($KyivNovaPoshtaWarehouseHMCash);

        $KyivNovaPoshtaAddressingHMWayForPay = new ShopDeliveryCityPayment();
        $KyivNovaPoshtaAddressingHMWayForPay->setShop($this->getReference('HM SHOP'));
        $KyivNovaPoshtaAddressingHMWayForPay->setShopDeliveryCity($this->getReference('ShopDeliveryCity:Nova Poshta:Addressing:HM:Kyiv'));
        $KyivNovaPoshtaAddressingHMWayForPay->setShopPayment($this->getReference('Payment:HM:WayForPay'));
        $manager->persist($KyivNovaPoshtaAddressingHMWayForPay);

        $KyivNovaPoshtaAddressingHMCash = new ShopDeliveryCityPayment();
        $KyivNovaPoshtaAddressingHMCash->setShop($this->getReference('HM SHOP'));
        $KyivNovaPoshtaAddressingHMCash->setShopDeliveryCity($this->getReference('ShopDeliveryCity:Nova Poshta:Addressing:HM:Kyiv'));
        $KyivNovaPoshtaAddressingHMCash->setShopPayment($this->getReference('Payment:HM:Cash'));
        $manager->persist($KyivNovaPoshtaAddressingHMCash);

        $OdesaCourierConsultantPoshWayForPay = new ShopDeliveryCityPayment();
        $OdesaCourierConsultantPoshWayForPay->setShop($this->getReference('POSH SHOP'));
        $OdesaCourierConsultantPoshWayForPay->setShopDeliveryCity($this->getReference('ShopDeliveryCity:Courier:Consultant:Posh:Odesa'));
        $OdesaCourierConsultantPoshWayForPay->setShopPayment($this->getReference('Payment:POSH:WayForPay'));
        $manager->persist($OdesaCourierConsultantPoshWayForPay);


        $OdesaCourierConsultantPoshCash = new ShopDeliveryCityPayment();
        $OdesaCourierConsultantPoshCash->setShop($this->getReference('POSH SHOP'));
        $OdesaCourierConsultantPoshCash->setShopDeliveryCity($this->getReference('ShopDeliveryCity:Courier:Consultant:Posh:Odesa'));
        $OdesaCourierConsultantPoshCash->setShopPayment($this->getReference('Payment:POSH:Cash'));
        $manager->persist($OdesaCourierConsultantPoshCash);

        $manager->flush();

        $this->setReference('ShopDeliveryCityPayment:Nova Poshta:Warehouse:HM:Kyiv:WayForPay', $KyivNovaPoshtaWarehouseHMWayForPay);
        $this->setReference('ShopDeliveryCityPayment:Nova Poshta:Warehouse:HM:Kyiv:Cash', $KyivNovaPoshtaWarehouseHMCash);
        $this->setReference('ShopDeliveryCityPayment:Nova Poshta:Addressing:HM:Kyiv:WayForPay', $KyivNovaPoshtaAddressingHMWayForPay);
        $this->setReference('ShopDeliveryCityPayment:Nova Poshta:Addressing:HM:Kyiv:Cash', $KyivNovaPoshtaAddressingHMCash);
        $this->setReference('ShopDeliveryCityPayment:Courier:Consultant:Posh:Odesa:WayForPay', $OdesaCourierConsultantPoshWayForPay);
        $this->setReference('ShopDeliveryCityPayment:Courier:Consultant:Posh:Odesa:Cash', $OdesaCourierConsultantPoshCash);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 5;
    }
}
