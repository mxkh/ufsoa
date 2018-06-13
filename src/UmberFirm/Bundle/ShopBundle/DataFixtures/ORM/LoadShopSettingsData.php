<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ShopBundle\Entity\ShopSettings;

/**
 * Class LoadShopSettingsData
 *
 * @package UmberFirm\Bundle\ShopBundle\DataFixtures\ORM
 */
class LoadShopSettingsData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        //Set Settings for HM Shop
        //is_social_login
        $HMShopSocialLogin = new ShopSettings();
        $HMShopSocialLogin->setShop($this->getReference('HM SHOP'));
        $HMShopSocialLogin->setAttribute($this->getReference('is_social_login'));
        $HMShopSocialLogin->setValue('true');
        //is_guest_buy
        $HMShopGuestBuy = new ShopSettings();
        $HMShopGuestBuy->setShop($this->getReference('HM SHOP'));
        $HMShopGuestBuy->setAttribute($this->getReference('is_guest_buy'));
        $HMShopGuestBuy->setValue('false');
        //is_one_click_ordering
        $HMShopOneClickOrdering = new ShopSettings();
        $HMShopOneClickOrdering->setShop($this->getReference('HM SHOP'));
        $HMShopOneClickOrdering->setAttribute($this->getReference('is_one_click_ordering'));
        $HMShopOneClickOrdering->setValue('true');
        //is_product_suggestion
        $HMShopProductSuggestion = new ShopSettings();
        $HMShopProductSuggestion->setShop($this->getReference('HM SHOP'));
        $HMShopProductSuggestion->setAttribute($this->getReference('is_product_suggestion'));
        $HMShopProductSuggestion->setValue('true');
        //is_triggered_email
        $HMShopTriggeredEmail = new ShopSettings();
        $HMShopTriggeredEmail->setShop($this->getReference('HM SHOP'));
        $HMShopTriggeredEmail->setAttribute($this->getReference('is_triggered_email'));
        $HMShopTriggeredEmail->setValue('true');
        //is_triggered_sms
        $HMShopTriggeredSms = new ShopSettings();
        $HMShopTriggeredSms->setShop($this->getReference('HM SHOP'));
        $HMShopTriggeredSms->setAttribute($this->getReference('is_triggered_sms'));
        $HMShopTriggeredSms->setValue('true');

        //Set Settings for POSH Shop
        //is_social_login
        $PoshShopSocialLogin = new ShopSettings();
        $PoshShopSocialLogin->setShop($this->getReference('POSH SHOP'));
        $PoshShopSocialLogin->setAttribute($this->getReference('is_social_login'));
        $PoshShopSocialLogin->setValue('true');
        //is_guest_buy
        $PoshShopGuestBuy = new ShopSettings();
        $PoshShopGuestBuy->setShop($this->getReference('POSH SHOP'));
        $PoshShopGuestBuy->setAttribute($this->getReference('is_guest_buy'));
        $PoshShopGuestBuy->setValue('true');
        //is_one_click_ordering
        $PoshShopOneClickOrdering = new ShopSettings();
        $PoshShopOneClickOrdering->setShop($this->getReference('POSH SHOP'));
        $PoshShopOneClickOrdering->setAttribute($this->getReference('is_one_click_ordering'));
        $PoshShopOneClickOrdering->setValue('true');
        //is_product_suggestion
        $PoshShopProductSuggestion = new ShopSettings();
        $PoshShopProductSuggestion->setShop($this->getReference('POSH SHOP'));
        $PoshShopProductSuggestion->setAttribute($this->getReference('is_product_suggestion'));
        $PoshShopProductSuggestion->setValue('true');
        //is_triggered_email
        $PoshShopTriggeredEmail = new ShopSettings();
        $PoshShopTriggeredEmail->setShop($this->getReference('POSH SHOP'));
        $PoshShopTriggeredEmail->setAttribute($this->getReference('is_triggered_email'));
        $PoshShopTriggeredEmail->setValue('false');
        //is_triggered_sms
        $PoshShopTriggeredSms = new ShopSettings();
        $PoshShopTriggeredSms->setShop($this->getReference('POSH SHOP'));
        $PoshShopTriggeredSms->setAttribute($this->getReference('is_triggered_sms'));
        $PoshShopTriggeredSms->setValue('false');

        //Set Settings for MD Shop
        //is_social_login
        $MDShopSocialLogin = new ShopSettings();
        $MDShopSocialLogin->setShop($this->getReference('MD SHOP'));
        $MDShopSocialLogin->setAttribute($this->getReference('is_social_login'));
        $MDShopSocialLogin->setValue('true');
        //is_guest_buy
        $MDShopGuestBuy = new ShopSettings();
        $MDShopGuestBuy->setShop($this->getReference('MD SHOP'));
        $MDShopGuestBuy->setAttribute($this->getReference('is_guest_buy'));
        $MDShopGuestBuy->setValue('false');
        //is_one_click_ordering
        $MDShopOneClickOrdering = new ShopSettings();
        $MDShopOneClickOrdering->setShop($this->getReference('MD SHOP'));
        $MDShopOneClickOrdering->setAttribute($this->getReference('is_one_click_ordering'));
        $MDShopOneClickOrdering->setValue('false');
        //is_product_suggestion
        $MDShopProductSuggestion = new ShopSettings();
        $MDShopProductSuggestion->setShop($this->getReference('MD SHOP'));
        $MDShopProductSuggestion->setAttribute($this->getReference('is_product_suggestion'));
        $MDShopProductSuggestion->setValue('true');
        //is_triggered_email
        $MDShopTriggeredEmail = new ShopSettings();
        $MDShopTriggeredEmail->setShop($this->getReference('MD SHOP'));
        $MDShopTriggeredEmail->setAttribute($this->getReference('is_triggered_email'));
        $MDShopTriggeredEmail->setValue('true');
        //is_triggered_sms
        $MDShopTriggeredSms = new ShopSettings();
        $MDShopTriggeredSms->setShop($this->getReference('MD SHOP'));
        $MDShopTriggeredSms->setAttribute($this->getReference('is_triggered_sms'));
        $MDShopTriggeredSms->setValue('false');

        //HM SHOP
        $manager->persist($HMShopSocialLogin);
        $manager->persist($HMShopGuestBuy);
        $manager->persist($HMShopOneClickOrdering);
        $manager->persist($HMShopProductSuggestion);
        $manager->persist($HMShopTriggeredEmail);
        $manager->persist($HMShopTriggeredSms);
        //POSH SHOP
        $manager->persist($PoshShopSocialLogin);
        $manager->persist($PoshShopGuestBuy);
        $manager->persist($PoshShopOneClickOrdering);
        $manager->persist($PoshShopProductSuggestion);
        $manager->persist($PoshShopTriggeredEmail);
        $manager->persist($PoshShopTriggeredSms);
        //MD SHOP
        $manager->persist($MDShopSocialLogin);
        $manager->persist($MDShopGuestBuy);
        $manager->persist($MDShopOneClickOrdering);
        $manager->persist($MDShopProductSuggestion);
        $manager->persist($MDShopTriggeredEmail);
        $manager->persist($MDShopTriggeredSms);

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 4;
    }
}
