<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ShopBundle\Entity\SocialProfileEnum;

/**
 * Class LoadSocialProfileEnumData
 *
 * @package UmberFirm\Bundle\ShopBundle\DataFixtures\ORM
 */
class LoadSocialProfileEnumData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $socialProfileEnum1 = new SocialProfileEnum();
        $socialProfileEnum1->setName('facebook.com', 'ua');
        $socialProfileEnum1->setName('facebook.com', 'ru');
        $socialProfileEnum1->setName('facebook.com', 'en');
        $socialProfileEnum1->setAlias('фейсбучик', 'ua');
        $socialProfileEnum1->setAlias('фейсбук', 'ru');
        $socialProfileEnum1->setAlias('facebook', 'en');
        $socialProfileEnum1->addStoreSocialProfile($this->getReference('STORESOCIALPROFILE_Ocean'));
        $socialProfileEnum1->addStoreSocialProfile($this->getReference('STORESOCIALPROFILE_HelenMarlenGroup'));
        $socialProfileEnum1->addStoreSocialProfile($this->getReference('STORESOCIALPROFILE_StorePoshuaWarehouse'));
        $socialProfileEnum1->mergeNewTranslations();

        $socialProfileEnum2 = new SocialProfileEnum();
        $socialProfileEnum2->setName('vk.com', 'en');
        $socialProfileEnum2->setName('vk.com', 'ua');
        $socialProfileEnum2->setName('vk.com', 'ru');
        $socialProfileEnum2->setAlias('фейсбучик', 'ua');
        $socialProfileEnum2->setAlias('вконтактi', 'ru');
        $socialProfileEnum2->setAlias('vkontakte', 'en');
        $socialProfileEnum2->addStoreSocialProfile($this->getReference('STORESOCIALPROFILE_Ocean'));
        $socialProfileEnum2->addStoreSocialProfile($this->getReference('STORESOCIALPROFILE_HelenMarlenGroup'));
        $socialProfileEnum2->addStoreSocialProfile($this->getReference('STORESOCIALPROFILE_StorePoshuaWarehouse'));
        $socialProfileEnum2->mergeNewTranslations();

        $socialProfileEnum3 = new SocialProfileEnum();
        $socialProfileEnum3->setName('ok.ru', 'en');
        $socialProfileEnum3->setName('ok.ru', 'ua');
        $socialProfileEnum3->setName('ok.ru', 'ru');
        $socialProfileEnum3->setAlias('однокласники', 'ua');
        $socialProfileEnum3->setAlias('одноклассники', 'ru');
        $socialProfileEnum3->setAlias('ok', 'en');
        $socialProfileEnum3->addStoreSocialProfile($this->getReference('STORESOCIALPROFILE_Ocean'));
        $socialProfileEnum3->addStoreSocialProfile($this->getReference('STORESOCIALPROFILE_HelenMarlenGroup'));
        $socialProfileEnum3->addStoreSocialProfile($this->getReference('STORESOCIALPROFILE_StorePoshuaWarehouse'));
        $socialProfileEnum3->mergeNewTranslations();

        $socialProfileEnum4 = new SocialProfileEnum();
        $socialProfileEnum4->setName('instagram.com', 'ua');
        $socialProfileEnum4->setName('instagram.com', 'ru');
        $socialProfileEnum4->setName('instagram.com', 'en');
        $socialProfileEnum4->setAlias('iнстаграм', 'ua');
        $socialProfileEnum4->setAlias('инстаграмм', 'ru');
        $socialProfileEnum4->setAlias('instagram', 'en');
        $socialProfileEnum4->addStoreSocialProfile($this->getReference('STORESOCIALPROFILE_Ocean'));
        $socialProfileEnum4->addStoreSocialProfile($this->getReference('STORESOCIALPROFILE_HelenMarlenGroup'));
        $socialProfileEnum4->addStoreSocialProfile($this->getReference('STORESOCIALPROFILE_StorePoshuaWarehouse'));
        $socialProfileEnum4->mergeNewTranslations();

        $manager->persist($socialProfileEnum1);
        $manager->persist($socialProfileEnum2);
        $manager->persist($socialProfileEnum3);
        $manager->persist($socialProfileEnum4);

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 7;
    }
}
