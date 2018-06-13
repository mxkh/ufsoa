<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\CommonBundle\Entity\Language;

/**
 * Class LoadLanguageData
 *
 * @package UmberFirm\Bundle\CommonBundle\DataFixtures\ORM
 */
class LoadLanguageData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $languageUA = new Language();
        $languageUA->setCode('ua');
        $languageUA->setName('Українська');

        $languageEN = new Language();
        $languageEN->setCode('en');
        $languageEN->setName('English');

        $languageRU = new Language();
        $languageRU->setCode('ru');
        $languageRU->setName('Русский');

        $languageES = new Language();
        $languageES->setCode('es');
        $languageES->setName('Español');

        $languageFR = new Language();
        $languageFR->setCode('fr');
        $languageFR->setName('France');

        $languageDE = new Language();
        $languageDE->setCode('de');
        $languageDE->setName('Deutsch');

        $languageCH = new Language();
        $languageCH->setCode('ch');
        $languageCH->setName('中文(简体)');

        $manager->persist($languageUA);
        $manager->persist($languageEN);
        $manager->persist($languageRU);
        $manager->persist($languageES);
        $manager->persist($languageFR);
        $manager->persist($languageDE);
        $manager->persist($languageCH);
        $manager->flush();

        $this->addReference('language_ua', $languageUA);
        $this->addReference('language_en', $languageEN);
        $this->addReference('language_ru', $languageRU);
        $this->addReference('language_es', $languageES);
        $this->addReference('language_fr', $languageFR);
        $this->addReference('language_de', $languageDE);
        $this->addReference('language_ch', $languageCH);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
