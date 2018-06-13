<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ProductBundle\Entity\Feature;
use UmberFirm\Bundle\ProductBundle\Entity\FeatureValue;

/**
 * Class LoadFeatureValueData
 *
 * @package UmberFirm\Bundle\ProductBundle\DataFixtures\ORM
 */
class LoadFeatureValueData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->loadCompositions($manager, $this->getReference('Feature:Compositions'));
        $this->loadStyles($manager, $this->getReference('Feature:Styles'));
        $this->loadProperties($manager, $this->getReference('Feature:Properties'));
    }

    /**
     * @param ObjectManager $manager
     * @param Feature $feature
     */
    protected function loadCompositions(ObjectManager $manager, Feature $feature): void
    {
        $polyester = new FeatureValue();
        $polyester->setFeature($feature)
            ->setValue('Поліестер', 'ua')
            ->setValue('Polyester', 'en')
            ->setValue('Полиэстер', 'ru')
            ->setValue('Poliéster', 'es')
            ->mergeNewTranslations();
        $manager->persist($polyester);

        $wool = new FeatureValue();
        $wool->setFeature($feature)
            ->setValue('Шерсть', 'ua')
            ->setValue('Wool', 'en')
            ->setValue('Шерсть', 'ru')
            ->setValue('Lana', 'es')
            ->mergeNewTranslations();
        $manager->persist($wool);

        $viscose = new FeatureValue();
        $viscose->setFeature($feature)
            ->setValue('Віскоза', 'ua')
            ->setValue('Viscose', 'en')
            ->setValue('Вискоза', 'ru')
            ->setValue('Viscosa', 'es')
            ->mergeNewTranslations();
        $manager->persist($viscose);

        $cotton = new FeatureValue();
        $cotton->setFeature($feature)
            ->setValue('Бавовна', 'ua')
            ->setValue('Cotton', 'en')
            ->setValue('Хлопок', 'ru')
            ->setValue('Algodón', 'es')
            ->mergeNewTranslations();
        $manager->persist($cotton);

        $silk = new FeatureValue();
        $silk->setFeature($feature)
            ->setValue('Шовк', 'ua')
            ->setValue('Silk', 'en')
            ->setValue('Шелк', 'ru')
            ->setValue('Seda', 'es')
            ->mergeNewTranslations();
        $manager->persist($silk);

        $manager->flush();

        $this->setReference('Feature:Compositions:Polyester', $polyester);
        $this->setReference('Feature:Compositions:Wool', $wool);
        $this->setReference('Feature:Compositions:Viscose', $viscose);
        $this->setReference('Feature:Compositions:Cotton', $cotton);
        $this->setReference('Feature:Compositions:Silk', $silk);
    }

    /**
     * @param ObjectManager $manager
     * @param Feature $feature
     */
    protected function loadStyles(ObjectManager $manager, Feature $feature): void
    {
        $classic = new FeatureValue();
        $classic->setFeature($feature)
            ->setValue('Класичний', 'ua')
            ->setValue('Classic', 'en')
            ->setValue('Классический', 'ru')
            ->setValue('Clásico', 'es')
            ->mergeNewTranslations();
        $manager->persist($classic);

        $casual = new FeatureValue();
        $casual->setFeature($feature)
            ->setValue('Повсякденный', 'ua')
            ->setValue('Casual', 'en')
            ->setValue('Повседневный', 'ru')
            ->setValue('Casual', 'es')
            ->mergeNewTranslations();
        $manager->persist($casual);

        $military = new FeatureValue();
        $military->setFeature($feature)
            ->setValue('Військовий', 'ua')
            ->setValue('Military', 'en')
            ->setValue('Военный', 'ru')
            ->setValue('Militar', 'es')
            ->mergeNewTranslations();
        $manager->persist($military);

        $manager->flush();

        $this->setReference('Feature:Styles:Classic', $classic);
        $this->setReference('Feature:Styles:Casual', $casual);
        $this->setReference('Feature:Styles:Military', $military);
    }

    /**
     * @param ObjectManager $manager
     * @param Feature $feature
     */
    protected function loadProperties(ObjectManager $manager, Feature $feature): void
    {
        $shortSleeve = new FeatureValue();
        $shortSleeve->setFeature($feature)
            ->setValue('Короткий Рукав', 'ua')
            ->setValue('Short Sleeve', 'en')
            ->setValue('Короткий Рукав', 'ru')
            ->setValue('Manga Corta', 'es')
            ->mergeNewTranslations();
        $manager->persist($shortSleeve);

        $colorfulDress = new FeatureValue();
        $colorfulDress->setFeature($feature)
            ->setValue('Барвисті Сукні', 'ua')
            ->setValue('Colorful Dress', 'en')
            ->setValue('Красочные Платья', 'ru')
            ->setValue('Vestido Colorido', 'es')
            ->mergeNewTranslations();
        $manager->persist($colorfulDress);

        $shortDress = new FeatureValue();
        $shortDress->setFeature($feature)
            ->setValue('Короткі Сукні', 'ua')
            ->setValue('Short Dress', 'en')
            ->setValue('Короткие Платье', 'ru')
            ->setValue('Vestido Corto', 'es')
            ->mergeNewTranslations();
        $manager->persist($shortDress);

        $manager->flush();

        $this->setReference('Feature:Properties:ShortSleeve', $shortSleeve);
        $this->setReference('Feature:Properties:ColorfulDress', $colorfulDress);
        $this->setReference('Feature:Properties:ShortDress', $shortDress);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 21;
    }
}
