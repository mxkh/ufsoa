<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierManufacturerMapping;

/**
 * Class LoadSupplierManufacturerMappingData
 *
 * @package UmberFirm\Bundle\SupplierBundle\DataFixtures\ORM
 */
class LoadSupplierManufacturerMappingData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $manufacturerMapping1 = new SupplierManufacturerMapping();
        $manufacturerMapping1->setSupplier($this->getReference('icon_supplier'));
        $manufacturerMapping1->setSupplierManufacturer('100001');
        $manufacturerMapping1->setManufacturer($this->getReference('manufacturer5'));
        $this->setReference('manufacturerMapping1', $manufacturerMapping1);

        $manufacturerMapping2 = new SupplierManufacturerMapping();
        $manufacturerMapping2->setSupplier($this->getReference('icon_supplier'));
        $manufacturerMapping2->setSupplierManufacturer('Milano');
        $manufacturerMapping2->setManufacturer($this->getReference('manufacturer2'));
        $this->setReference('manufacturerMapping2', $manufacturerMapping2);

        $manufacturerMapping3 = new SupplierManufacturerMapping();
        $manufacturerMapping3->setSupplier($this->getReference('flow_supplier'));
        $manufacturerMapping3->setSupplierManufacturer('ALC');
        $manufacturerMapping3->setManufacturer($this->getReference('manufacturer3'));
        $this->setReference('manufacturerMapping3', $manufacturerMapping3);

        $manager->persist($manufacturerMapping1);
        $manager->persist($manufacturerMapping2);
        $manager->persist($manufacturerMapping3);

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
