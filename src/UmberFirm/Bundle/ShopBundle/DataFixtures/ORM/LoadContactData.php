<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ShopBundle\Entity\Contact;

/**
 * Class LoadContactData
 *
 * @package UmberFirm\Bundle\ShopBundle\DataFixtures\ORM
 */
class LoadContactData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $phoneHelenMarlenOcean = new Contact();
        $phoneHelenMarlenOcean->setValue('+38 044 247 70 78');
        $phoneHelenMarlenOcean->addStore($this->getReference('STORE_Ocean'));

        $phoneHelenMarlenMandarin = new Contact();
        $phoneHelenMarlenMandarin->setValue('+38 044 247 70 81');
        $phoneHelenMarlenMandarin->addStore($this->getReference('STORE_Mandarin'));

        $phoneHelenMarlenGucci = new Contact();
        $phoneHelenMarlenGucci->setValue('+38 044 247 70 73');
        $phoneHelenMarlenGucci->addStore($this->getReference('STORE_Gucci'));

        $phoneHelenMarlenSalvatoreFerragamo = new Contact();
        $phoneHelenMarlenSalvatoreFerragamo->setValue('+38 044 247 70 74');
        $phoneHelenMarlenSalvatoreFerragamo->addStore($this->getReference('STORE_SalvatoreFerragamo'));

        $phoneHelenMarlenShowRoom = new Contact();
        $phoneHelenMarlenShowRoom->setValue('+38 044 591 26 18');
        $phoneHelenMarlenShowRoom->addStore($this->getReference('STORE_HelenMarlenShowroom'));

        $phonePoshua = new Contact();
        $phonePoshua->setValue('+38 044 461 54 61');
        $phonePoshua->addStore($this->getReference('STORE_StorePoshuaWarehouse'));

        $manager->persist($phoneHelenMarlenOcean);
        $manager->persist($phoneHelenMarlenMandarin);
        $manager->persist($phoneHelenMarlenGucci);
        $manager->persist($phoneHelenMarlenSalvatoreFerragamo);
        $manager->persist($phonePoshua);
        $manager->persist($phoneHelenMarlenShowRoom);

        $manager->flush();

        $this->addReference('CONTACT_phoneHelenMarlenOcean', $phoneHelenMarlenOcean);
        $this->addReference('CONTACT_phoneHelenMarlenMandarin', $phoneHelenMarlenMandarin);
        $this->addReference('CONTACT_phoneHelenMarlenGucci', $phoneHelenMarlenGucci);
        $this->addReference('CONTACT_phoneHelenMarlenSalvatoreFerragamo', $phoneHelenMarlenSalvatoreFerragamo);
        $this->addReference('CONTACT_phoneHelenMarlenShowRoom', $phoneHelenMarlenShowRoom);
        $this->addReference('CONTACT_phonePoshuaWarehouse', $phonePoshua);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 5;
    }
}
