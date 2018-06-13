<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\MediaBundle\Entity\MimeType;

/**
 * Class LoadMimeTypeData
 *
 * @package UmberFirm\Bundle\MediaBundle\DataFixtures\ORM
 */
class LoadMimeTypeData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $imagePng = (new MimeType())
            ->setMediaEnum($this->getReference('MediaEnum:Image'))
            ->setName('png')
            ->setTemplate('image/png');
        $manager->persist($imagePng);

        $imageJpeg = (new MimeType())
            ->setMediaEnum($this->getReference('MediaEnum:Image'))
            ->setName('jpeg')
            ->setTemplate('image/jpeg');
        $manager->persist($imageJpeg);

        $imageJpg = (new MimeType())
            ->setMediaEnum($this->getReference('MediaEnum:Image'))
            ->setName('jpg')
            ->setTemplate('image/jpg');
        $manager->persist($imageJpg);

        $imagePjpg = (new MimeType())
            ->setMediaEnum($this->getReference('MediaEnum:Image'))
            ->setName('pjpg')
            ->setTemplate('image/pjpg');
        $manager->persist($imagePjpg);

        $imageGif = (new MimeType())
            ->setMediaEnum($this->getReference('MediaEnum:Image'))
            ->setName('gif')
            ->setTemplate('image/gif');
        $manager->persist($imageGif);

        $videoMp4 = (new MimeType())
            ->setMediaEnum($this->getReference('MediaEnum:Video'))
            ->setName('mp4')
            ->setTemplate('video/mp4');
        $manager->persist($videoMp4);

        $manager->flush();

        $this->setReference('MimeType:Image:PNG', $imagePng);
        $this->setReference('MimeType:Image:JPEG', $imageJpeg);
        $this->setReference('MimeType:Image:JPG', $imageJpeg);
        $this->setReference('MimeType:Image:GIF', $imageGif);
        $this->setReference('MimeType:Video:MP4', $videoMp4);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 31;
    }
}
