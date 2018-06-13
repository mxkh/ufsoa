<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ShopBundle\Entity\StoreEnum;

/**
 * Class LoadStoreEnumData
 *
 * @package UmberFirm\Bundle\ShopBundle\DataFixtures\ORM
 */
class LoadStoreEnumData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $storeEnum1 = new StoreEnum();
        $storeEnum1->setName('магазин', 'ua');
        $storeEnum1->setName('магазин', 'ru');
        $storeEnum1->setName('store', 'en');
        $storeEnum1->addStore($this->getReference('STORE_Ocean'));
        $storeEnum1->addStore($this->getReference('STORE_Mandarin'));
        $storeEnum1->addStore($this->getReference('STORE_Gucci'));
        $storeEnum1->addStore($this->getReference('STORE_SalvatoreFerragamo'));
        $storeEnum1->mergeNewTranslations();

        $storeEnum2 = new StoreEnum();
        $storeEnum2->setName('шоурум', 'ua');
        $storeEnum2->setName('шоурум', 'ru');
        $storeEnum2->setName('showroom', 'en');
        $storeEnum2->addStore($this->getReference('STORE_HelenMarlenShowroom'));
        $storeEnum2->mergeNewTranslations();

        $storeEnum3 = new StoreEnum();
        $storeEnum3->setName('склад', 'ua');
        $storeEnum3->setName('склад', 'ru');
        $storeEnum3->setName('warehouse', 'en');
        $storeEnum3->addStore($this->getReference('STORE_StorePoshuaWarehouse'));
        $storeEnum3->mergeNewTranslations();

        $manager->persist($storeEnum1);
        $manager->persist($storeEnum2);
        $manager->persist($storeEnum3);

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 2;
    }
}
