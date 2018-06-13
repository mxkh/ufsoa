<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierStoreMapping;

/**
 * Class LoadSupplierStoreMappingData
 *
 * @package UmberFirm\Bundle\SupplierBundle\DataFixtures\ORM
 */
class LoadSupplierStoreMappingData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $IconOceanMapping = new SupplierStoreMapping();
        $IconOceanMapping->setStore($this->getReference('STORE_Ocean'));
        $IconOceanMapping->setSupplierStore('Ocean Plaza HM');
        $IconOceanMapping->setSupplier($this->getReference('icon_supplier'));

        $FrolovOceanMapping = new SupplierStoreMapping();
        $FrolovOceanMapping->setStore($this->getReference('STORE_Ocean'));
        $FrolovOceanMapping->setSupplierStore('Ocean Plaza HM');
        $FrolovOceanMapping->setSupplier($this->getReference('frolov_supplier'));

        $FrolovMandirnMapping = new SupplierStoreMapping();
        $FrolovMandirnMapping->setStore($this->getReference('STORE_Mandarin'));
        $FrolovMandirnMapping->setSupplierStore('Mandarin HM');
        $FrolovMandirnMapping->setSupplier($this->getReference('frolov_supplier'));

        $manager->persist($IconOceanMapping);
        $manager->persist($FrolovOceanMapping);
        $manager->persist($FrolovMandirnMapping);

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 4;
    }
}
