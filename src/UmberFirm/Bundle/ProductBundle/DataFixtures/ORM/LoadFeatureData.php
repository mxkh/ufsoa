<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ProductBundle\Entity\Feature;

/**
 * Class LoadFeatureData
 *
 * @package UmberFirm\Bundle\ProductBundle\DataFixtures\ORM
 */
class LoadFeatureData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $compositions = new Feature();
        $compositions
            ->setName('Склад', 'ua')
            ->setName('Compositions', 'en')
            ->setName('Состав', 'ru')
            ->setName('Сomposiciones', 'es')
            ->mergeNewTranslations();
        $manager->persist($compositions);

        $styles = new Feature();
        $styles
            ->setName('Стилі', 'ua')
            ->setName('Styles', 'en')
            ->setName('Стили', 'ru')
            ->setName('Estilos', 'es')
            ->mergeNewTranslations();
        $manager->persist($styles);

        $properties = new Feature();
        $properties
            ->setName('Властивості', 'ua')
            ->setName('Properties', 'en')
            ->setName('Cвойства', 'ru')
            ->setName('Propiedades', 'es')
            ->mergeNewTranslations();
        $manager->persist($properties);

        $manager->flush();

        $this->setReference('Feature:Compositions', $compositions);
        $this->setReference('Feature:Styles', $styles);
        $this->setReference('Feature:Properties', $properties);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 20;
    }
}
