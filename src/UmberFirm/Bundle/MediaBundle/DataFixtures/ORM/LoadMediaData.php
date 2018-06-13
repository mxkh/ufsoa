<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\MediaBundle\Entity\Media;

/**
 * Class LoadMediaData
 *
 * @package UmberFirm\Bundle\MediaBundle\DataFixtures\ORM
 */
class LoadMediaData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $jpg = new Media();
        $jpg->setMediaEnum($this->getReference('MediaEnum:Image'));
        $jpg->setMimeType('image/jpg');
        $jpg->setFilename('1.jpg');
        $jpg->setExtension('jpg');
        $manager->persist($jpg);

        $mp4 = new Media();
        $mp4->setMediaEnum($this->getReference('MediaEnum:Video'));
        $mp4->setMimeType('video/mp4');
        $mp4->setFilename('1.mp4');
        $mp4->setExtension('mp4');
        $manager->persist($mp4);

        $manager->flush();

        $this->addReference('Media:jpg', $jpg);
        $this->addReference('Media:mp4', $mp4);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 31;
    }
}
