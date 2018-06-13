<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ProductBundle\Entity\ProductFeature;

/**
 * Class LoadFeatureData
 *
 * @package UmberFirm\Bundle\ProductBundle\DataFixtures\ORM
 */
class LoadProductFeatureData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $productFeature = new ProductFeature();
        $productFeature->setProduct($this->getReference('Product:platie'))
            ->setFeature($this->getReference('Feature:Properties'))
            ->addProductFeatureValue($this->getReference('Feature:Properties:ColorfulDress'))
            ->addProductFeatureValue($this->getReference('Feature:Properties:ShortDress'));
        $manager->persist($productFeature);

        $productFeature1 = new ProductFeature();
        $productFeature1->setProduct($this->getReference('Product:platie'))
            ->setFeature($this->getReference('Feature:Styles'))
            ->addProductFeatureValue($this->getReference('Feature:Properties:ShortSleeve'));
        $manager->persist($productFeature1);

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 22;
    }
}
