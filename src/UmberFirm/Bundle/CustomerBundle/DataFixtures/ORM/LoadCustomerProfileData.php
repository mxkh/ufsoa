<?php

namespace UmberFirm\Bundle\CustomerBundle\DataFixtures\ORM;

use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerProfile;

/**
 * Class LoadCustomerProfileData
 *
 * @package UmberFirm\Bundle\CustomerBundle\DataFixtures\ORM
 */
class LoadCustomerProfileData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $johnDoe = new CustomerProfile();
        $johnDoe->setCustomer($this->getReference('CustomerBundle:Customer'));
        $johnDoe->setFirstname('John');
        $johnDoe->setLastname('Doe');
        $johnDoe->setBirthday(new DateTime);
        $johnDoe->setGender($this->getReference('Gender:Men'));
        $manager->persist($johnDoe);

        $hannibalLecter = new CustomerProfile();
        $hannibalLecter->setCustomer($this->getReference('CustomerBundle:Customer2'));
        $hannibalLecter->setFirstname('Hannibal');
        $hannibalLecter->setLastname('Lecter');
        $hannibalLecter->setBirthday(new DateTime);
        $hannibalLecter->setGender($this->getReference('Gender:Men'));
        $manager->persist($hannibalLecter);

        $ororoMunroe = new CustomerProfile();
        $ororoMunroe->setCustomer($this->getReference('CustomerBundle:Customer3'));
        $ororoMunroe->setFirstname('Ororo');
        $ororoMunroe->setLastname('Munroe');
        $ororoMunroe->setBirthday(new DateTime);
        $ororoMunroe->setGender($this->getReference('Gender:Women'));
        $manager->persist($ororoMunroe);

        $manager->flush();

        $this->setReference('CustomerProfile:JohnDoe', $johnDoe);
        $this->setReference('CustomerProfile:HannibalLecter', $hannibalLecter);
        $this->setReference('CustomerProfile:OroroMunroe', $ororoMunroe);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 14;
    }
}
