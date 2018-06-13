<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\CommonBundle\Entity\City;

/**
 * Class LoadCityData
 *
 * @package UmberFirm\Bundle\CommonBundle\DataFixtures\ORM
 */
class LoadCityData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $kyiv = new City();
        $kyiv->setName('Kyiv');
        $kyiv->setRef('8d5a980d-391c-11dd-90d9-001a92567626');
        $manager->persist($kyiv);

        $odesa = new City();
        $odesa->setName('Odesa');
        $odesa->setRef('db5c88d0-391c-11dd-90d9-001a92567626');
        $manager->persist($odesa);

        $manager->flush();

        $this->setReference('City:Kyiv', $kyiv);
        $this->setReference('City:Odesa', $odesa);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
