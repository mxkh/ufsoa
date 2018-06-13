<?php

namespace UmberFirm\Bundle\CustomerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\CommonBundle\Entity\Gender;

/**
 * Class LoadGenderData
 *
 * @package UmberFirm\Bundle\CustomerBundle\DataFixtures\ORM
 */
class LoadGenderData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $gender = new Gender();
        $gender->setName('Men', 'ua');
        $gender->mergeNewTranslations();
        $manager->persist($gender);

        $gender2 = new Gender();
        $gender2->setName('Women', 'ru');
        $gender2->mergeNewTranslations();
        $manager->persist($gender2);

        $gender3 = new Gender();
        $gender3->setName('Children', 'en');
        $gender3->mergeNewTranslations();
        $manager->persist($gender3);

        $manager->flush();

        $this->setReference('Gender:Men', $gender);
        $this->setReference('Gender:Women', $gender2);
        $this->setReference('Gender:Children', $gender3);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 12;
    }
}
