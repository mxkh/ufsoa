<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ProductBundle\Entity\Attribute;

/**
 * Class LoadAttributeData
 *
 * @package UmberFirm\Bundle\ProductBundle\DataFixtures\ORM
 */
class LoadAttributeData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $sizeS = new Attribute();
        $sizeS->setAttributeGroup($this->getReference('AttributeGroup:Size'))
            ->setName('S', 'ua')
            ->setName('S', 'en')
            ->setName('S', 'es')
            ->setName('S', 'ru')
            ->mergeNewTranslations();
        $manager->persist($sizeS);

        $sizeM = new Attribute();
        $sizeM->setAttributeGroup($this->getReference('AttributeGroup:Size'))
            ->setName('M', 'ua')
            ->setName('M', 'en')
            ->setName('M', 'es')
            ->setName('M', 'ru')
            ->mergeNewTranslations();
        $manager->persist($sizeM);

        $sizeL = new Attribute();
        $sizeL->setAttributeGroup($this->getReference('AttributeGroup:Size'))
            ->setName('L', 'ua')
            ->setName('L', 'en')
            ->setName('L', 'es')
            ->setName('L', 'ru')
            ->mergeNewTranslations();
        $manager->persist($sizeL);

        $colorRed = new Attribute();
        $colorRed->setAttributeGroup($this->getReference('AttributeGroup:Color'))
            ->setColor('#FF0000')
            ->setName('Червоний', 'ua')
            ->setName('Red', 'en')
            ->setName('Rojo', 'es')
            ->setName('Красный', 'ru')
            ->mergeNewTranslations();
        $manager->persist($colorRed);

        $colorGreen = new Attribute();
        $colorGreen->setAttributeGroup($this->getReference('AttributeGroup:Color'))
            ->setColor('#00FF00')
            ->setName('Зелений', 'ua')
            ->setName('Green', 'en')
            ->setName('Verde', 'es')
            ->setName('Зеленый', 'ru')
            ->mergeNewTranslations();
        $manager->persist($colorGreen);

        $colorBlue = new Attribute();
        $colorBlue->setAttributeGroup($this->getReference('AttributeGroup:Color'))
            ->setColor('#0000FF')
            ->setName('Синій', 'ua')
            ->setName('Blue', 'en')
            ->setName('Azul', 'es')
            ->setName('Синий', 'ru')
            ->mergeNewTranslations();
        $manager->persist($colorBlue);

        $manager->flush();

        $this->setReference('Attribute:Size:S', $sizeS);
        $this->setReference('Attribute:Size:M', $sizeM);
        $this->setReference('Attribute:Size:L', $sizeL);

        $this->setReference('Attribute:Color:Red', $colorRed);
        $this->setReference('Attribute:Color:Green', $colorGreen);
        $this->setReference('Attribute:Color:Blue', $colorBlue);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 13;
    }
}
