<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ShopBundle\Entity\SettingsAttribute;

/**
 * Class LoadSettingsAttributeData
 *
 * @package UmberFirm\Bundle\ShopBundle\DataFixtures\ORM
 */
class LoadSettingsAttributeData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $socialLoginAttribute = new SettingsAttribute();
        $socialLoginAttribute->setName('is_social_login');
        $socialLoginAttribute->setType('boolean');

        $guestBuyAttribute = new SettingsAttribute();
        $guestBuyAttribute->setName('is_guest_buy');
        $guestBuyAttribute->setType('boolean');

        $oneClickOrderingAttribute = new SettingsAttribute();
        $oneClickOrderingAttribute->setName('is_one_click_ordering');
        $oneClickOrderingAttribute->setType('boolean');

        $productSuggestionAttribute = new SettingsAttribute();
        $productSuggestionAttribute->setName('is_product_suggestion');
        $productSuggestionAttribute->setType('boolean');

        $triggeredEmailAttribute = new SettingsAttribute();
        $triggeredEmailAttribute->setName('is_triggered_email');
        $triggeredEmailAttribute->setType('boolean');

        $triggeredSmsAttribute = new SettingsAttribute();
        $triggeredSmsAttribute->setName('is_triggered_sms');
        $triggeredSmsAttribute->setType('boolean');

        $manager->persist($socialLoginAttribute);
        $manager->persist($guestBuyAttribute);
        $manager->persist($oneClickOrderingAttribute);
        $manager->persist($productSuggestionAttribute);
        $manager->persist($triggeredEmailAttribute);
        $manager->persist($triggeredSmsAttribute);

        $manager->flush();

        $this->addReference('is_social_login', $socialLoginAttribute);
        $this->addReference('is_guest_buy', $guestBuyAttribute);
        $this->addReference('is_one_click_ordering', $oneClickOrderingAttribute);
        $this->addReference('is_product_suggestion', $productSuggestionAttribute);
        $this->addReference('is_triggered_email', $triggeredEmailAttribute);
        $this->addReference('is_triggered_sms', $triggeredSmsAttribute);
    }

    /**
     * Get the order of this fixture
     *
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }
}
