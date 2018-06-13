<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CategoryBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\CategoryBundle\Entity\CategorySeo;

/**
 * Class LoadCategorySeoData
 *
 * @package UmberFirm\Bundle\CategoryBundle\DataFixtures\ORM
 */
class LoadCategorySeoData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $foodSeo = new CategorySeo();
        /** @var string $locale */
        $locale = $foodSeo->getCurrentLocale();
        $foodSeo->setTitle('title food', $locale);
        $foodSeo->setDescription('description food', $locale);
        $foodSeo->setKeywords('keywords food', $locale);
        $foodSeo->setCategory($this->getReference('Category:Food'));
        $foodSeo->setShop($this->getReference('HM SHOP'));
        $foodSeo->mergeNewTranslations();

        $fruitsSeo = new CategorySeo();
        /** @var string $locale */
        $locale = $fruitsSeo->getCurrentLocale();
        $fruitsSeo->setTitle('title fruits', $locale);
        $fruitsSeo->setDescription('description fruits', $locale);
        $fruitsSeo->setKeywords('keywords fruits', $locale);
        $fruitsSeo->setCategory($this->getReference('Category:Fruits'));
        $fruitsSeo->setShop($this->getReference('HM SHOP'));
        $fruitsSeo->mergeNewTranslations();

        $vegetablesSeo = new CategorySeo();
        /** @var string $locale */
        $locale = $vegetablesSeo->getCurrentLocale();
        $vegetablesSeo->setTitle('title Vegetables', $locale);
        $vegetablesSeo->setDescription('description Vegetables', $locale);
        $vegetablesSeo->setKeywords('keywords Vegetables', $locale);
        $vegetablesSeo->setCategory($this->getReference('Category:Vegetables'));
        $vegetablesSeo->setShop($this->getReference('HM SHOP'));
        $vegetablesSeo->mergeNewTranslations();

        $carrotsSeo = new CategorySeo();
        /** @var string $locale */
        $locale = $carrotsSeo->getCurrentLocale();
        $carrotsSeo->setTitle('title Carrots', $locale);
        $carrotsSeo->setDescription('description Carrots', $locale);
        $carrotsSeo->setKeywords('keywords Carrots', $locale);
        $carrotsSeo->setCategory($this->getReference('Category:Carrots'));
        $carrotsSeo->setShop($this->getReference('HM SHOP'));
        $carrotsSeo->mergeNewTranslations();

        $manager->persist($foodSeo);
        $manager->persist($fruitsSeo);
        $manager->persist($vegetablesSeo);
        $manager->persist($carrotsSeo);
        $manager->flush();

        $this->setReference('Category:Food:Seo', $foodSeo);
        $this->setReference('Category:Fruits:Seo', $fruitsSeo);
        $this->setReference('Category:Vegetables:Seo', $vegetablesSeo);
        $this->setReference('Category:Carrots:Seo', $carrotsSeo);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 3;
    }
}
