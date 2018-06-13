<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ProductBundle\Entity\ProductSeo;

/**
 * Class LoadProductSeoData
 *
 * @package UmberFirm\Bundle\ProductBundle\DataFixtures\ORM
 */
class LoadProductSeoData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $productSeo1 = new ProductSeo();
        /** @var string $locale */
        $locale = $productSeo1->getDefaultLocale();
        $productSeo1->setKeywords('keyword pidjack', $locale);
        $productSeo1->setTitle('title pidjack', $locale);
        $productSeo1->setDescription('description pidjack', $locale);
        $productSeo1->setProduct($this->getReference('Product:pidjack'));
        $productSeo1->setShop($this->getReference('HM SHOP'));
        $productSeo1->mergeNewTranslations();

        $productSeo2 = new ProductSeo();
        /** @var string $locale */
        $locale = $productSeo2->getDefaultLocale();
        $productSeo2->setKeywords('keyword sharf', $locale);
        $productSeo2->setTitle('title sharf', $locale);
        $productSeo2->setDescription('description sharf', $locale);
        $productSeo2->setProduct($this->getReference('Product:sharf'));
        $productSeo2->setShop($this->getReference('HM SHOP'));
        $productSeo2->mergeNewTranslations();

        $productSeo3 = new ProductSeo();
        /** @var string $locale */
        $locale = $productSeo3->getDefaultLocale();
        $productSeo3->setKeywords('keyword obruch', $locale);
        $productSeo3->setTitle('title obruch', $locale);
        $productSeo3->setDescription('description obruch', $locale);
        $productSeo3->setProduct($this->getReference('Product:obruch'));
        $productSeo3->setShop($this->getReference('HM SHOP'));
        $productSeo3->mergeNewTranslations();

        $manager->persist($productSeo1);
        $manager->persist($productSeo2);
        $manager->persist($productSeo3);

        $manager->flush();

        $this->setReference('seo:Product:pidjack', $productSeo1);
        $this->setReference('seo:Product:sharf', $productSeo2);
        $this->setReference('seo:Product:obruch', $productSeo3);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 6;
    }
}
