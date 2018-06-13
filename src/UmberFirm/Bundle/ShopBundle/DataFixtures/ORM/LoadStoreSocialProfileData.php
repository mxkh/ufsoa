<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ShopBundle\Entity\StoreSocialProfile;

/**
 * Class LoadStoreSocialProfileData
 *
 * @package UmberFirm\Bundle\ShopBundle\DataFixtures\ORM
 */
class LoadStoreSocialProfileData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $storeSocialProfile1 = new StoreSocialProfile();
        $storeSocialProfile1->setValue('https://www.facebook.com/OceanPlaza/');
        $storeSocialProfile1->addStore($this->getReference('STORE_Ocean'));

        $storeSocialProfile2 = new StoreSocialProfile();
        $storeSocialProfile2->setValue('https://www.facebook.com/HelenMarlenGroup/');
        $storeSocialProfile2->addStore($this->getReference('STORE_Ocean'));
        $storeSocialProfile2->addStore($this->getReference('STORE_Mandarin'));
        $storeSocialProfile2->addStore($this->getReference('STORE_Gucci'));
        $storeSocialProfile2->addStore($this->getReference('STORE_SalvatoreFerragamo'));
        $storeSocialProfile2->addStore($this->getReference('STORE_HelenMarlenShowroom'));

        $storeSocialProfile3 = new StoreSocialProfile();
        $storeSocialProfile3->setValue('https://www.facebook.com/www.posh.ua/');
        $storeSocialProfile3->addStore($this->getReference('STORE_StorePoshuaWarehouse'));

        $manager->persist($storeSocialProfile1);
        $manager->persist($storeSocialProfile2);
        $manager->persist($storeSocialProfile3);

        $manager->flush();

        $this->addReference('STORESOCIALPROFILE_Ocean', $storeSocialProfile1);
        $this->addReference('STORESOCIALPROFILE_HelenMarlenGroup', $storeSocialProfile2);
        $this->addReference('STORESOCIALPROFILE_StorePoshuaWarehouse', $storeSocialProfile3);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 4;
    }
}
