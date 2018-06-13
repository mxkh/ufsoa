<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ProductBundle\Entity\ProductImport;

/**
 * Class LoadProductImportData
 *
 * @package UmberFirm\Bundle\ProductBundle\DataFixtures\ORM
 */
class LoadProductImportData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $product = new ProductImport();
        $product->setSupplierReference('014838 116');
        $product->setProduct($this->getReference('Product:pidjack'));
        $product->setShop($this->getReference('POSH SHOP'));
        $product->setSupplier($this->getReference('icon_supplier'));
        $manager->persist($product);

        $product2 = new ProductImport();
        $product2->setSupplierReference('014838 117');
        $product2->setProduct($this->getReference('Product:sharf'));
        $product2->setShop($this->getReference('POSH SHOP'));
        $product2->setSupplier($this->getReference('icon_supplier'));
        $manager->persist($product2);

        $product3 = new ProductImport();
        $product3->setSupplierReference('014838 118');
        $product3->setProduct($this->getReference('Product:obruch'));
        $product3->setShop($this->getReference('POSH SHOP'));
        $product3->setSupplier($this->getReference('flow_supplier'));
        $manager->persist($product3);

        $product4 = new ProductImport();
        $product4->setSupplierReference('014838 119');
        $product4->setProduct($this->getReference('Product:platie'));
        $product4->setShop($this->getReference('POSH SHOP'));
        $product4->setSupplier($this->getReference('flow_supplier'));
        $manager->persist($product4);

        $manager->flush();

        $this->setReference('ProductImport:pidjack', $product);
        $this->setReference('ProductImport:sharf', $product2);
        $this->setReference('ProductImport:obruch', $product3);
        $this->setReference('ProductImport:platie', $product4);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 5;
    }
}
