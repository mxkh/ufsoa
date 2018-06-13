<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\CustomerBundle\Entity\SocialNetwork;

/**
 * Class LoadSocialNetworkData
 *
 * @package UmberFirm\Bundle\CustomerBundle\DataFixtures\ORM
 */
class LoadSocialNetworkData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $facebook = new SocialNetwork();
        $facebook->setName('facebook');
        $manager->persist($facebook);

        $google = new SocialNetwork();
        $google->setName('google');
        $manager->persist($google);

        $manager->flush();

        $this->addReference('SocialNetwork:Facebook', $facebook);
        $this->addReference('SocialNetwork:Google', $google);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 30;
    }
}
