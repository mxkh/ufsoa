<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariantMedia;

/**
 * Class LoadProductVariantMediaData
 *
 * @package UmberFirm\Bundle\ProductBundle\DataFixtures\ORM
 */
class LoadProductVariantMediaData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $productVariantMedia1 = new ProductVariantMedia();
        $productVariantMedia1->setProductMedia($this->getReference('ProductMedia1'));
        $productVariantMedia1->setProductVariant($this->getReference('ProductVariant1'));
        $manager->persist($productVariantMedia1);

        $productVariantMedia2 = new ProductVariantMedia();
        $productVariantMedia2->setProductMedia($this->getReference('ProductMedia2'));
        $productVariantMedia2->setProductVariant($this->getReference('ProductVariant2'));
        $manager->persist($productVariantMedia2);

        $productVariantMedia3 = new ProductVariantMedia();
        $productVariantMedia3->setProductMedia($this->getReference('ProductMedia3'));
        $productVariantMedia3->setProductVariant($this->getReference('ProductVariant3'));
        $manager->persist($productVariantMedia3);

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 33;
    }
}
