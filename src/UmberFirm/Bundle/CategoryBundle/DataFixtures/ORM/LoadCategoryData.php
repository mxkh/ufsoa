<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CategoryBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\CategoryBundle\Entity\Category;

/**
 * Class LoadCategoryData
 *
 * @package UmberFirm\Bundle\CategoryBundle\DataFixtures\ORM
 */
class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $food = new Category();
        $food->setTitle('Їжа', 'ua');
        $food->setTitle('Еда', 'ru');
        $food->setTitle('Comida', 'es');
        $food->setTitle('Food', 'en');
        $food->mergeNewTranslations();

        $fruits = new Category();
        $fruits->setTitle('Фрукти', 'ua');
        $fruits->setTitle('Фрукты', 'ru');
        $fruits->setTitle('Frutas', 'es');
        $fruits->setTitle('Fruits', 'en');
        $fruits->setParent($food);
        $fruits->mergeNewTranslations();

        $vegetables = new Category();
        $vegetables->setTitle('Овочі', 'ua');
        $vegetables->setTitle('Овощи', 'ru');
        $vegetables->setTitle('Vegetales', 'es');
        $vegetables->setTitle('Vegetables', 'en');
        $vegetables->setParent($food);
        $vegetables->mergeNewTranslations();

        $carrots = new Category();
        $carrots->setTitle('Морква', 'ua');
        $carrots->setTitle('Морковь', 'ru');
        $carrots->setTitle('Zanahorias', 'es');
        $carrots->setTitle('Carrots', 'en');
        $carrots->setParent($vegetables);
        $carrots->mergeNewTranslations();

        $manager->persist($food);
        $manager->persist($fruits);
        $manager->persist($vegetables);
        $manager->persist($carrots);
        $manager->flush();

        $this->setReference('Category:Food', $food);
        $this->setReference('Category:Fruits', $fruits);
        $this->setReference('Category:Vegetables', $vegetables);
        $this->setReference('Category:Carrots', $carrots);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
