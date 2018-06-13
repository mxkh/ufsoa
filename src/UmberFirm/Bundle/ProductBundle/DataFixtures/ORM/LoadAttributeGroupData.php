<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroup;

/**
 * Class LoadAttributeGroupData
 *
 * @package UmberFirm\Bundle\ProductBundle\DataFixtures\ORM
 */
class LoadAttributeGroupData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $size = new AttributeGroup();
        $size->setIsColorGroup(false)
            ->setAttributeGroupEnum($this->getReference('AttributeGroupEnum:Select'))
            ->setName('Розмiр', 'ua')
            ->setPublicName('Розмiр', 'ua')
            ->setName('Size', 'en')
            ->setPublicName('Size', 'en')
            ->setName('Tamaño', 'es')
            ->setPublicName('Tamaño', 'es')
            ->setName('Размер', 'ru')
            ->setPublicName('Размер', 'ru')
            ->mergeNewTranslations();
        $manager->persist($size);

        $shoesSize = new AttributeGroup();
        $shoesSize->setIsColorGroup(false)
            ->setAttributeGroupEnum($this->getReference('AttributeGroupEnum:Select'))
            ->setName('Розмiр взуття', 'ua')
            ->setPublicName('Розмiр взуття', 'ua')
            ->setName('Shoes Size', 'en')
            ->setPublicName('Shoes Size', 'en')
            ->setName('Talla de zapatos', 'es')
            ->setPublicName('Talla de zapatos', 'es')
            ->setName('Размер обуви', 'ru')
            ->setPublicName('Размер обуви', 'ru')
            ->mergeNewTranslations();
        $manager->persist($shoesSize);

        $color = new AttributeGroup();
        $color->setIsColorGroup(true)
            ->setAttributeGroupEnum($this->getReference('AttributeGroupEnum:Color'))
            ->setName('Колір', 'ua')
            ->setPublicName('Колір', 'ua')
            ->setName('Color', 'en')
            ->setPublicName('Color', 'en')
            ->setName('Color', 'es')
            ->setPublicName('Color', 'es')
            ->setName('Цвет', 'ru')
            ->setPublicName('Цвет', 'ru')
            ->mergeNewTranslations();
        $manager->persist($color);

        $manager->flush();

        $this->setReference('AttributeGroup:Size', $size);
        $this->setReference('AttributeGroup:ShoesSize', $shoesSize);
        $this->setReference('AttributeGroup:Color', $color);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 12;
    }
}
