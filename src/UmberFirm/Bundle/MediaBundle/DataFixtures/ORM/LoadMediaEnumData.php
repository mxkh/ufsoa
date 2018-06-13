<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\MediaBundle\Entity\MediaEnum;

/**
 * Class LoadMediaEnumData
 *
 * @package UmberFirm\Bundle\MediaBundle\DataFixtures\ORM
 */
class LoadMediaEnumData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $image = new MediaEnum();
        $image->setName('image');
        $manager->persist($image);

        $video = new MediaEnum();
        $video->setName('video');
        $manager->persist($video);

        $application = new MediaEnum();
        $application->setName('application');
        $manager->persist($application);

        $manager->flush();

        $this->addReference('MediaEnum:Image', $image);
        $this->addReference('MediaEnum:Video', $video);
        $this->addReference('MediaEnum:Application', $application);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 30;
    }
}
