<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ProductBundle\Entity\ProductMedia;

/**
 * Class LoadProductMediaData
 *
 * @package UmberFirm\Bundle\ProductBundle\DataFixtures\ORM
 */
class LoadProductMediaData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $productPidjack1 = new ProductMedia();
        $productPidjack1->setProduct($this->getReference('Product:pidjack'));
        $productPidjack1->setShop($this->getReference('HM SHOP'));
        $productPidjack1->setMedia($this->getReference('Media:jpg'));
        $productPidjack1->setAlt('Пиджак', 'ru');
        $productPidjack1->setAlt('А jacket', 'en');
        $productPidjack1->mergeNewTranslations();
        $manager->persist($productPidjack1);

        $productPidjack2 = new ProductMedia();
        $productPidjack2->setProduct($this->getReference('Product:pidjack'));
        $productPidjack2->setShop($this->getReference('HM SHOP'));
        $productPidjack2->setMedia($this->getReference('Media:mp4'));
        $productPidjack2->setAlt('Пиджак', 'ru');
        $productPidjack2->setAlt('А jacket', 'en');
        $productPidjack2->mergeNewTranslations();
        $manager->persist($productPidjack2);

        $productPidjack3 = new ProductMedia();
        $productPidjack3->setProduct($this->getReference('Product:pidjack'));
        $productPidjack3->setShop($this->getReference('POSH SHOP'));
        $productPidjack3->setMedia($this->getReference('Media:jpg'));
        $productPidjack3->setAlt('Пиджак', 'ru');
        $productPidjack3->setAlt('А jacket', 'en');
        $productPidjack3->mergeNewTranslations();
        $manager->persist($productPidjack3);

        $manager->flush();

        $this->setReference('ProductMedia1', $productPidjack1);
        $this->setReference('ProductMedia2', $productPidjack2);
        $this->setReference('ProductMedia3', $productPidjack3);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 32;
    }
}
