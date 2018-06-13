<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\DeliveryBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\DeliveryBundle\Entity\CityDelivery;

/**
 * Class LoadCityDeliveryData
 *
 * @package UmberFirm\Bundle\DeliveryBundle\DataFixtures\ORM
 */
class LoadCityDeliveryData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $novaPoshtaKyiv = new CityDelivery();
        $novaPoshtaKyiv->setCity($this->getReference('City:Kyiv'));
        $novaPoshtaKyiv->setDeliveryGroup($this->getReference('DeliveryGroup:Nova Poshta'));
        $manager->persist($novaPoshtaKyiv);

        $courierOdesa = new CityDelivery();
        $courierOdesa->setCity($this->getReference('City:Odesa'));
        $courierOdesa->setDeliveryGroup($this->getReference('DeliveryGroup:Courier'));
        $manager->persist($courierOdesa);

        $manager->flush();

        $this->setReference('Nova Poshta:Kyiv', $novaPoshtaKyiv);
        $this->setReference('Courier:Odesa', $courierOdesa);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 2;
    }
}
