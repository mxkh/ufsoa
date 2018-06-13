<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPaymentSettings;

/**
 * Class LoadShopPaymentSettingsData
 *
 * @package UmberFirm\Bundle\ShopBundle\DataFixtures\ORM
 */
class LoadShopPaymentSettingsData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $settings = new ShopPaymentSettings();
        $settings->setPublicKey('test_merch_n1');
        $settings->setPrivateKey('flk3409refn54t54t*FNJRET');
        $settings->setReturnUrl('ufsoa.dev/return_url');
        $settings->setMerchantAuthType('SimpleSignature');
        $settings->setMerchantTransactionType('AUTH');
        $settings->setTestMode(true);
        $settings->setDomainName('posh.ua');
        $settings->setShopPayment($this->getReference('Payment:POSH:WayForPay'));
        $manager->persist($settings);

        $manager->flush();

        $this->setReference('Payment:WayForPay:Settings', $settings);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 4;
    }
}
