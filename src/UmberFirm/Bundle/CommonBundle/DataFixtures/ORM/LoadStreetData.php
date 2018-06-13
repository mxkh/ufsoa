<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\CommonBundle\Entity\Street;

/**
 * Class LoadStreetData
 *
 * @package UmberFirm\Bundle\CommonBundle\DataFixtures\ORM
 */
class LoadStreetData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $street1 = new Street();
        $street1->setName('Велика Житомирська');
        $street1->setRef('20ad5d7a-4149-11dd-9198-001d60451983');
        $street1->setType('вул.');
        $street1->setCity($this->getReference('City:Kyiv'));
        $manager->persist($street1);

        $street2 = new Street();
        $street2->setName('Басистого Адмірала');
        $street2->setRef('52d7cef5-b9a8-11df-bcca-000c29f46a62');
        $street2->setType('вул.');
        $street1->setCity($this->getReference('City:Odesa'));
        $manager->persist($street2);

        $manager->flush();

        $this->setReference('City:Street1', $street1);
        $this->setReference('City:Street2', $street2);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 2;
    }
}
