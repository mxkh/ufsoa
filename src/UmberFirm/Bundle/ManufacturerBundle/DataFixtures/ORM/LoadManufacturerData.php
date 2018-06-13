<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;

/**
 * Class LoadManufacturerData
 *
 * @package UmberFirm\Bundle\ShopBundle\DataFixtures\ORM
 */
class LoadManufacturerData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $manufacturer = new Manufacturer();
        $manufacturer->setName('10 Crosby Derek Lam');
        $manufacturer->setAddress(
            'Derek Lam Online Customer Service 3040 East Ana Street Rancho Dominguez CA 90221',
            'ua'
        );
        $manufacturer->setWebsite('www.dereklam.com');
        $manufacturer->addShop($this->getReference('HM SHOP'));
        $manufacturer->addShop($this->getReference('POSH SHOP'));
        $manufacturer->mergeNewTranslations();
        $this->setReference('manufacturer', $manufacturer);
        $manager->persist($manufacturer);

        $manufacturer2 = new Manufacturer();
        $manufacturer2->setName('43Milano');
        $manufacturer2->setAddress('Via Alessandro Manzoni, 43, 20121 Milano, Italy', 'ua');
        $manufacturer2->setWebsite('www.43cycles.com');
        $manufacturer2->addShop($this->getReference('MD SHOP'));
        $manufacturer2->addShop($this->getReference('POSH SHOP'));
        $manufacturer2->mergeNewTranslations();
        $this->setReference('manufacturer2', $manufacturer2);
        $manager->persist($manufacturer2);

        $manufacturer3 = new Manufacturer();
        $manufacturer3->setName('A.L.C.');
        $manufacturer3->setAddress('210 Fifth Ave, 2nd Floor, New York, NY 10010', 'ua');
        $manufacturer3->setWebsite('www.alcltd.com');
        $manufacturer3->addShop($this->getReference('HM SHOP'));
        $manufacturer3->addShop($this->getReference('POSH SHOP'));
        $manufacturer3->addShop($this->getReference('MD SHOP'));
        $manufacturer3->mergeNewTranslations();
        $this->setReference('manufacturer3', $manufacturer3);
        $manager->persist($manufacturer3);

        $manufacturer4 = new Manufacturer();
        $manufacturer4->setName('Alberto Guardiani');
        $manufacturer4->setAddress('Milano (MI) Corso Venezia, 16 Italy', 'ua');
        $manufacturer4->setWebsite('www.albertoguardiani.com');
        $manufacturer4->addShop($this->getReference('HM SHOP'));
        $manufacturer4->addShop($this->getReference('POSH SHOP'));
        $manufacturer4->addShop($this->getReference('MD SHOP'));
        $manufacturer4->mergeNewTranslations();
        $this->setReference('manufacturer4', $manufacturer4);
        $manager->persist($manufacturer4);

        $manufacturer5 = new Manufacturer();
        $manufacturer5->setName('Alexander Wang');
        $manufacturer5->setAddress('43-44 ALBEMARLE STREET LONDON W1S 4JJ, UNITED KINGDOM 020-3727-5568', 'ua');
        $manufacturer5->setWebsite('www.alexanderwang.com');
        $manufacturer5->addShop($this->getReference('HM SHOP'));
        $manufacturer5->addShop($this->getReference('POSH SHOP'));
        $manufacturer5->addShop($this->getReference('MD SHOP'));
        $manufacturer5->mergeNewTranslations();
        $this->setReference('manufacturer5', $manufacturer5);
        $manager->persist($manufacturer5);

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 3;
    }
}
