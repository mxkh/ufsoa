<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;

/**
 * Class LoadProductVariantData
 *
 * @package UmberFirm\Bundle\ProductBundle\DataFixtures\ORM
 */
class LoadProductVariantData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $productVariant1 = new ProductVariant();
        $productVariant1->setProduct($this->getReference('Product:platie'))
            ->setPrice(1000.00)
            ->setSalePrice(999.99)
            ->addProductVariantAttribute($this->getReference('Attribute:Size:S'))
            ->addProductVariantAttribute($this->getReference('Attribute:Color:Red'));
        $manager->persist($productVariant1);

        $productVariant2 = new ProductVariant();
        $productVariant2->setProduct($this->getReference('Product:pidjack'))
            ->setPrice(1000.00)
            ->setSalePrice(999.98)
            ->addProductVariantAttribute($this->getReference('Attribute:Size:M'))
            ->addProductVariantAttribute($this->getReference('Attribute:Color:Green'));
        $manager->persist($productVariant2);

        $productVariant3 = new ProductVariant();
        $productVariant3->setProduct($this->getReference('Product:pidjack'))
            ->setPrice(1000.00)
            ->setSalePrice(600.98)
            ->addProductVariantAttribute($this->getReference('Attribute:Size:L'))
            ->addProductVariantAttribute($this->getReference('Attribute:Color:Blue'));
        $manager->persist($productVariant3);

        $manager->flush();

        $this->setReference('ProductVariant1', $productVariant1);
        $this->setReference('ProductVariant2', $productVariant2);
        $this->setReference('ProductVariant3', $productVariant3);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 23;
    }
}
