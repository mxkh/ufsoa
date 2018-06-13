<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ProductBundle\Entity\Product;

/**
 * Class LoadProductData
 *
 * @package UmberFirm\Bundle\ProductBundle\DataFixtures\ORM
 */
class LoadProductData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $product = new Product();
        $product->setManufacturer($this->getReference('manufacturer'));
        $product->setOutOfStock(true);
        $product->setShop($this->getReference('POSH SHOP'));
        $product->addCategory($this->getReference('Category:Carrots'));
        $manager->persist($product);

        $product4 = new Product();
        $product4->setManufacturer($this->getReference('manufacturer4'));
        $product4->setOutOfStock(true);
        $product4->setShop($this->getReference('HM SHOP'));
        $product4->addCategory($this->getReference('Category:Carrots'));
        $manager->persist($product4);

        $product2 = new Product();
        $product2->setManufacturer($this->getReference('manufacturer2'));
        $product2->setOutOfStock(true);
        $product2->setShop($this->getReference('MD SHOP'));
        $product2->addCategory($this->getReference('Category:Carrots'));
        $manager->persist($product2);

        $product8 = new Product();
        $product8->setManufacturer($this->getReference('manufacturer'));
        $product8->setOutOfStock(true);
        $product8->setShop($this->getReference('HM SHOP'));
        $product8->addCategory($this->getReference('Category:Carrots'));
        $manager->persist($product8);

        $product9 = new Product();
        $product9->setManufacturer($this->getReference('manufacturer4'));
        $product9->setOutOfStock(true);
        $product9->setShop($this->getReference('MD SHOP'));
        $product9->addCategory($this->getReference('Category:Carrots'));
        $manager->persist($product9);

        $product10 = new Product();
        $product10->setManufacturer($this->getReference('manufacturer5'));
        $product10->setOutOfStock(true);
        $product10->setShop($this->getReference('POSH SHOP'));
        $product10->addCategory($this->getReference('Category:Carrots'));
        $manager->persist($product10);

        $product3 = new Product();
        $product3->setManufacturer($this->getReference('manufacturer3'));
        $product3->setOutOfStock(true);
        $product3->setShop($this->getReference('HM SHOP'));
        $product3->addCategory($this->getReference('Category:Carrots'));
        $manager->persist($product3);

        $product5 = new Product();
        $product5->setManufacturer($this->getReference('manufacturer5'));
        $product5->setOutOfStock(true);
        $product5->setShop($this->getReference('MD SHOP'));
        $product5->addCategory($this->getReference('Category:Carrots'));
        $manager->persist($product5);

        $product6 = new Product();
        $product6->setManufacturer($this->getReference('manufacturer2'));
        $product6->setOutOfStock(true);
        $product6->setShop($this->getReference('HM SHOP'));
        $product6->addCategory($this->getReference('Category:Carrots'));
        $manager->persist($product6);

        $product7 = new Product();
        $product7->setManufacturer($this->getReference('manufacturer3'));
        $product7->setOutOfStock(true);
        $product7->setShop($this->getReference('POSH SHOP'));
        $product7->addCategory($this->getReference('Category:Carrots'));
        $manager->persist($product7);

        $manager->flush();

        $this->setReference('Product:pidjack', $product);
        $this->setReference('Product:sharf', $product2);
        $this->setReference('Product:obruch', $product3);
        $this->setReference('Product:platie', $product4);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 4;
    }
}
