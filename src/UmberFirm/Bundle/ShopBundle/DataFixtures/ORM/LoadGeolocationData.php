<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ShopBundle\Entity\Geolocation;

/**
 * Class LoadGeolocationData
 *
 * @package UmberFirm\Bundle\ShopBundle\DataFixtures\ORM
 */
class LoadGeolocationData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $geolocation1 = new Geolocation();
        $geolocation1->setLatitude(50.4121237);
        $geolocation1->setLongitude(30.5204166);
        $geolocation1->addStore($this->getReference('STORE_Ocean'));

        $geolocation2 = new Geolocation();
        $geolocation2->setLatitude(50.453256);
        $geolocation2->setLongitude(30.518013);
        $geolocation2->addStore($this->getReference('STORE_HelenMarlenShowroom'));

        $geolocation3 = new Geolocation();
        $geolocation3->setLatitude(50.441216);
        $geolocation3->setLongitude(30.521409);
        $geolocation3->addStore($this->getReference('STORE_Mandarin'));

        $geolocation4 = new Geolocation();
        $geolocation4->setLatitude(50.447057);
        $geolocation4->setLongitude(30.525552);
        $geolocation4->addStore($this->getReference('STORE_Gucci'));

        $geolocation5 = new Geolocation();
        $geolocation5->setLatitude(50.447057);
        $geolocation5->setLongitude(30.525552);
        $geolocation5->addStore($this->getReference('STORE_SalvatoreFerragamo'));

        $geolocation6 = new Geolocation();
        $geolocation6->setLatitude(50.405044);
        $geolocation6->setLongitude(30.679423);
        $geolocation6->addStore($this->getReference('STORE_StorePoshuaWarehouse'));

        $manager->persist($geolocation1);
        $manager->persist($geolocation2);
        $manager->persist($geolocation3);
        $manager->persist($geolocation4);
        $manager->persist($geolocation5);
        $manager->persist($geolocation6);

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 9;
    }
}
