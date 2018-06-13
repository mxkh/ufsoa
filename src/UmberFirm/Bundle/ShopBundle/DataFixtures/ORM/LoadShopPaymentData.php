<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPayment;

/**
 * Class LoadShopPaymentData
 *
 * @package UmberFirm\Bundle\ShopBundle\DataFixtures\ORM
 */
class LoadShopPaymentData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $wayForPayPosh = new ShopPayment();
        $wayForPayPosh->setPayment($this->getReference('Payment:WayForPay'));
        $wayForPayPosh->setShop($this->getReference('POSH SHOP'));
        $manager->persist($wayForPayPosh);

        $cashPosh = new ShopPayment();
        $cashPosh->setPayment($this->getReference('Payment:cash'));
        $cashPosh->setShop($this->getReference('POSH SHOP'));
        $manager->persist($cashPosh);

        $wayForPayHM = new ShopPayment();
        $wayForPayHM->setPayment($this->getReference('Payment:WayForPay'));
        $wayForPayHM->setShop($this->getReference('HM SHOP'));
        $manager->persist($wayForPayHM);

        $cashHM = new ShopPayment();
        $cashHM->setPayment($this->getReference('Payment:cash'));
        $cashHM->setShop($this->getReference('HM SHOP'));
        $manager->persist($cashHM);

        $wayForPayMD = new ShopPayment();
        $wayForPayMD->setPayment($this->getReference('Payment:WayForPay'));
        $wayForPayMD->setShop($this->getReference('MD SHOP'));
        $manager->persist($wayForPayMD);

        $cashMD = new ShopPayment();
        $cashMD->setPayment($this->getReference('Payment:cash'));
        $cashMD->setShop($this->getReference('MD SHOP'));
        $manager->persist($cashMD);

        $manager->flush();

        $this->setReference('Payment:POSH:WayForPay', $wayForPayPosh);
        $this->setReference('Payment:POSH:Cash', $cashPosh);
        $this->setReference('Payment:HM:WayForPay', $wayForPayHM);
        $this->setReference('Payment:HM:Cash', $cashHM);
        $this->setReference('Payment:MD:WayForPay', $wayForPayMD);
        $this->setReference('Payment:MD:Cash', $cashMD);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 3;
    }
}
