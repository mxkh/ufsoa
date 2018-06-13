<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\DeliveryBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\DeliveryBundle\Entity\Delivery;

/**
 * Class LoadDeliveryData
 *
 * @package UmberFirm\Bundle\DeliveryBundle\DataFixtures\ORM
 */
class LoadDeliveryData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $novaPoshtaWarehouse = new Delivery();
        $novaPoshtaWarehouse->setCode('nova_poshta_warehouse');
        $novaPoshtaWarehouse->setName('Nova Poshta Warehouse', 'en');
        $novaPoshtaWarehouse->setDescription('Nova Poshta Warehouse description', 'en');
        $novaPoshtaWarehouse->setGroup($this->getReference('DeliveryGroup:Nova Poshta'));
        $novaPoshtaWarehouse->mergeNewTranslations();
        $manager->persist($novaPoshtaWarehouse);

        $novaPoshtaAddressing = new Delivery();
        $novaPoshtaAddressing->setCode('nova_poshta_addressing');
        $novaPoshtaAddressing->setName('Nova Poshta Addressing', 'en');
        $novaPoshtaAddressing->setDescription('Nova Poshta Addressing description', 'en');
        $novaPoshtaAddressing->mergeNewTranslations();
        $novaPoshtaAddressing->setGroup($this->getReference('DeliveryGroup:Nova Poshta'));
        $manager->persist($novaPoshtaAddressing);

        $courierConsultant = new Delivery();
        $courierConsultant->setCode('courier_consultant');
        $courierConsultant->setName('Courier Consultant', 'en');
        $courierConsultant->setDescription('Courier Consultant description', 'en');
        $courierConsultant->mergeNewTranslations();
        $courierConsultant->setGroup($this->getReference('DeliveryGroup:Courier'));
        $manager->persist($courierConsultant);

        $manager->flush();

        $this->setReference('DeliveryGroup:Nova Poshta Warehouse', $novaPoshtaWarehouse);
        $this->setReference('DeliveryGroup:Nova Poshta Addressing', $novaPoshtaAddressing);
        $this->setReference('Delivery:Courier Consultant', $courierConsultant);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 2;
    }
}
