<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ShopBundle\Entity\ContactEnum;

/**
 * Class LoadContactEnumData
 *
 * @package UmberFirm\Bundle\ShopBundle\DataFixtures\ORM
 */
class LoadContactEnumData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $contactEnum = new ContactEnum();
        $contactEnum->setValue('телефон', 'ua');
        $contactEnum->setValue('телефон', 'ru');
        $contactEnum->setValue('phone', 'en');
        $contactEnum->addContact($this->getReference('CONTACT_phoneHelenMarlenOcean'));
        $contactEnum->addContact($this->getReference('CONTACT_phoneHelenMarlenMandarin'));
        $contactEnum->addContact($this->getReference('CONTACT_phoneHelenMarlenGucci'));
        $contactEnum->addContact($this->getReference('CONTACT_phoneHelenMarlenSalvatoreFerragamo'));
        $contactEnum->addContact($this->getReference('CONTACT_phoneHelenMarlenShowRoom'));
        $contactEnum->addContact($this->getReference('CONTACT_phonePoshuaWarehouse'));
        $contactEnum->mergeNewTranslations();

        $manager->persist($contactEnum);
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 8;
    }
}
