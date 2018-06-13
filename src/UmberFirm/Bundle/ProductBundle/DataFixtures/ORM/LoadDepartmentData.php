<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ProductBundle\Entity\Department;

/**
 * Class LoadDepartmentData
 *
 * @package UmberFirm\Bundle\ProductBundle\DataFixtures\ORM
 */
class LoadDepartmentData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $department1 = new Department();
        $department1
            ->setProductVariant($this->getReference('ProductVariant1'))
            ->setQuantity(1)
            ->setPrice(1000.00)
            ->setSalePrice(999.99)
            ->setEan13('0123456789012')
            ->setSupplier($this->getReference('flow_supplier'))
            ->setStore($this->getReference('STORE_Ocean'));
        $manager->persist($department1);

        $department2 = new Department();
        $department2
            ->setProductVariant($this->getReference('ProductVariant2'))
            ->setQuantity(1)
            ->setPrice(1000.00)
            ->setSalePrice(999.98)
            ->setEan13('0123456789013')
            ->setSupplier($this->getReference('flow_supplier'))
            ->setStore($this->getReference('STORE_Ocean'));
        $manager->persist($department2);

        $department3 = new Department();
        $department3
            ->setProductVariant($this->getReference('ProductVariant3'))
            ->setQuantity(1)
            ->setPrice(1000.00)
            ->setSalePrice(600.98)
            ->setEan13('0123456789014')
            ->setSupplier($this->getReference('flow_supplier'))
            ->setStore($this->getReference('STORE_Ocean'));
        $manager->persist($department3);

        $manager->flush();

        $this->setReference('Department1', $department1);
        $this->setReference('Department2', $department2);
        $this->setReference('Department3', $department3);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 24;
    }
}
