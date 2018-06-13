<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SubscriptionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\SubscriptionBundle\Entity\Subscriber;

/**
 * Class LoadSubscriberData
 *
 * @package UmberFirm\Bundle\SubscriptionBundle\DataFixtures\ORM
 */
class LoadSubscriberData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $john = new Subscriber();
        $john->setEmail('john@doe.com');

        $michel = new Subscriber();
        $michel->setEmail('michel@doe.com');

        $manager->persist($john);
        $manager->persist($michel);
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
