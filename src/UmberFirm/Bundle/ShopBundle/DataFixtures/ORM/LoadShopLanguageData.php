<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ShopBundle\Entity\ShopLanguage;

/**
 * Class LoadShopLanguageData
 *
 * @package UmberFirm\Bundle\ShopBundle\DataFixtures\ORM
 */
class LoadShopLanguageData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $hmUa = new ShopLanguage();
        $hmUa->setShop($this->getReference('HM SHOP'));
        $hmUa->setLanguage($this->getReference('language_ua'));
        $hmUa->setIsDefault(true);

        $hmRu = new ShopLanguage();
        $hmRu->setShop($this->getReference('HM SHOP'));
        $hmRu->setLanguage($this->getReference('language_ru'));

        $hmEn = new ShopLanguage();
        $hmEn->setShop($this->getReference('HM SHOP'));
        $hmEn->setLanguage($this->getReference('language_en'));

        $poshEn = new ShopLanguage();
        $poshEn->setShop($this->getReference('POSH SHOP'));
        $poshEn->setLanguage($this->getReference('language_en'));

        $poshEs = new ShopLanguage();
        $poshEs->setShop($this->getReference('POSH SHOP'));
        $poshEs->setLanguage($this->getReference('language_es'));
        $poshEs->setIsDefault(true);

        $poshFr = new ShopLanguage();
        $poshFr->setShop($this->getReference('POSH SHOP'));
        $poshFr->setLanguage($this->getReference('language_fr'));

        $MDShopDE = new ShopLanguage();
        $MDShopDE->setShop($this->getReference('MD SHOP'));
        $MDShopDE->setLanguage($this->getReference('language_de'));
        $MDShopDE->setIsDefault(true);

        $MDShopCH = new ShopLanguage();
        $MDShopCH->setShop($this->getReference('MD SHOP'));
        $MDShopCH->setLanguage($this->getReference('language_ch'));

        $manager->persist($hmUa);
        $manager->persist($hmRu);
        $manager->persist($hmEn);
        $manager->persist($poshEn);
        $manager->persist($poshEs);
        $manager->persist($poshFr);
        $manager->persist($MDShopDE);
        $manager->persist($MDShopCH);

        $manager->flush();

        $this->setReference('HM SHOP ua', $hmUa);
        $this->setReference('HM SHOP ru', $hmRu);
        $this->setReference('HM SHOP en', $hmEn);
        $this->setReference('POSH SHOP en', $poshEn);
        $this->setReference('POSH SHOP es', $poshEs);
        $this->setReference('POSH SHOP fr', $poshFr);
        $this->setReference('MD SHOP de', $MDShopDE);
        $this->setReference('MD SHOP ch', $MDShopCH);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 3;
    }
}
