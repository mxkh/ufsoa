<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\CommonBundle\Entity\Subject;

/**
 * Class LoadSubjectData
 *
 * @package UmberFirm\Bundle\CommonBundle\DataFixtures\ORM
 */
class LoadSubjectData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $subject1 = new Subject();
        $subject1->setName('Пожелание', 'ru');
        $subject1->setIsActive(true);

        $subject2 = new Subject();
        $subject2->setName('Предложение', 'ru');
        $subject2->setIsActive(true);

        $manager->persist($subject1);
        $manager->persist($subject2);
        $manager->flush();

        $this->addReference('Subject:Wishes', $subject1);
        $this->addReference('Subject:Offer', $subject2);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 14;
    }
}
