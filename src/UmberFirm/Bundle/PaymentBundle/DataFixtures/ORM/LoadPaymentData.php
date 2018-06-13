<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PaymentBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\PaymentBundle\Entity\Payment;

/**
 * Class LoadPaymentTypeData
 *
 * @package UmberFirm\Bundle\PaymentBundle\DataFixtures\ORM
 */
class LoadPaymentData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $cash = new Payment();
        $cash->setCode('cash');
        $cash->setName('Оплата готівкою', 'ua');
        $cash->setType(Payment::OFFLINE);
        $cash->setDescription('Оплата готівкою при отримані замовлення кур\'єром.', 'ua');
        $cash->mergeNewTranslations();
        $manager->persist($cash);

        $wayForPay = new Payment();
        $wayForPay->setCode('WayForPay');
        $wayForPay->setName('Visa/MasterCard WayForPay', 'ua');
        $wayForPay->setType(Payment::ONLINE);
        $wayForPay->setDescription('Швидкий ти зручний спосіб оплати. Підтвердження приходить протягом декількох хвилин.', 'ua');
        $wayForPay->mergeNewTranslations();
        $manager->persist($wayForPay);

        $manager->flush();

        $this->setReference('Payment:cash', $cash);
        $this->setReference('Payment:WayForPay', $wayForPay);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
