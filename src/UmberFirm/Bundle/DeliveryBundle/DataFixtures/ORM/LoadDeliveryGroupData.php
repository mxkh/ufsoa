<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\DeliveryBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\DeliveryBundle\Entity\DeliveryGroup;

/**
 * Class LoadDeliveryGroupData
 *
 * @package UmberFirm\Bundle\DeliveryBundle\DataFixtures\ORM
 */
class LoadDeliveryGroupData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $novaPoshta = new DeliveryGroup();
        $novaPoshta->setCode('nova_poshta');
        $novaPoshta->setName('Nova Poshta', 'en');
        $novaPoshta->setDescription('Nova Poshta description', 'en');
        $novaPoshta->setName('Nova Poshta', 'en');
        $novaPoshta->mergeNewTranslations();
        $manager->persist($novaPoshta);

        $courier = new DeliveryGroup();
        $courier->setCode('courier');
        $courier->setName('Courier', 'en');
        $courier->setDescription('Courier description', 'en');
        $courier->mergeNewTranslations();
        $manager->persist($courier);

        $manager->flush();

        $this->setReference('DeliveryGroup:Nova Poshta', $novaPoshta);
        $this->setReference('DeliveryGroup:Courier', $courier);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
