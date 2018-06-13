<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\CommonBundle\Entity\Feedback;

/**
 * Class LoadFeedbackData
 *
 * @package UmberFirm\Bundle\CommonBundle\DataFixtures\ORM
 */
class LoadFeedbackData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $feedback1 = new Feedback();
        $feedback1->setName('Joe Doe');
        $feedback1->setEmail('joedoe@gmail.com');
        $feedback1->setPhone('380501234567');
        $feedback1->setCustomer($this->getReference('CustomerBundle:Customer'));
        $feedback1->setMessage('Lorem Ipsum - это текст-"рыба", часто используемый в печати и вэб-дизайне. Lorem Ipsum является стандартной "рыбой" для текстов на латинице с начала XVI века.');
        $feedback1->setShop($this->getReference('HM SHOP'));
        $feedback1->setSource('https://google.com.ua');
        $feedback1->setSubject($this->getReference('Subject:Wishes'));

        $feedback2 = new Feedback();
        $feedback2->setName('Joe Doe');
        $feedback2->setEmail('joedoe@gmail.com');
        $feedback2->setPhone('380501234567');
        $feedback2->setMessage('Lorem Ipsum - это текст-"рыба", часто используемый в печати и вэб-дизайне. Lorem Ipsum является стандартной "рыбой" для текстов на латинице с начала XVI века.');
        $feedback2->setShop($this->getReference('HM SHOP'));
        $feedback2->setSource('https://google.com.ua');
        $feedback2->setSubject($this->getReference('Subject:Offer'));

        $manager->persist($feedback1);
        $manager->persist($feedback2);
        $manager->flush();

        $this->addReference('Feedback:1', $feedback1);
        $this->addReference('Feedback:2', $feedback2);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 15;
    }
}
