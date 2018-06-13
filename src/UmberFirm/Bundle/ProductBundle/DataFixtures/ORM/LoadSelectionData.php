<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ProductBundle\Entity\Selection;

/**
 * Class LoadSelectionData
 *
 * @package UmberFirm\Bundle\ProductBundle\DataFixtures\ORM
 */
class LoadSelectionData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $selectionWinter = new Selection();
        /** @var string $locale */
        $locale = $selectionWinter->getDefaultLocale();
        $selectionWinter->setName('Winter Selection', $locale);
        $selectionWinter->setDescription('Winter Selection Description', $locale);
        $selectionWinter->setIsActive(true);
        $selectionWinter->setShop($this->getReference('HM SHOP'));
        $selectionWinter->mergeNewTranslations();
        $manager->persist($selectionWinter);

        $selectionSummer = new Selection();
        $selectionSummer->setName('Summer Selection', $locale);
        $selectionSummer->setDescription('Summer Selection Description', $locale);
        $selectionSummer->setIsActive(true);
        $selectionSummer->setShop($this->getReference('HM SHOP'));
        $selectionSummer->mergeNewTranslations();
        $manager->persist($selectionSummer);

        $selectionFall = new Selection();
        $selectionFall->setName('Fall Selection', $locale);
        $selectionFall->setDescription('Fall Selection Description', $locale);
        $selectionFall->setIsActive(true);
        $selectionFall->setShop($this->getReference('POSH SHOP'));
        $selectionFall->mergeNewTranslations();
        $manager->persist($selectionFall);

        $manager->flush();
        $this->setReference('Selection:Winter', $selectionWinter);
        $this->setReference('Selection:Summer', $selectionSummer);
        $this->setReference('Selection:Fall', $selectionFall);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 24;
    }
}
