<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\CommonBundle\Entity\Currency;

/**
 * Class LoadCurrencyData
 *
 * @package UmberFirm\Bundle\CommonBundle\DataFixtures\ORM
 */
class LoadCurrencyData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $currencyUSD = new Currency();
        $currencyUSD->setCode('USD');
        $currencyUSD->setName('US Dollar');

        $currencyUAH = new Currency();
        $currencyUAH->setCode('UAH');
        $currencyUAH->setName('Гривня');

        $currencyRUB = new Currency();
        $currencyRUB->setCode('RUB');
        $currencyRUB->setName('Рубль');

        $currencyEUR = new Currency();
        $currencyEUR->setCode('EUR');
        $currencyEUR->setName('Euro');

        $currencyGBP = new Currency();
        $currencyGBP->setCode('GBP');
        $currencyGBP->setName('Pound');

        $currencyCNY = new Currency();
        $currencyCNY->setCode('CNY');
        $currencyCNY->setName('元');

        $manager->persist($currencyUSD);
        $manager->persist($currencyUAH);
        $manager->persist($currencyRUB);
        $manager->persist($currencyEUR);
        $manager->persist($currencyGBP);
        $manager->persist($currencyCNY);
        $manager->flush();

        $this->setReference('currency_USD', $currencyUSD);
        $this->setReference('currency_UAH', $currencyUAH);
        $this->setReference('currency_RUB', $currencyRUB);
        $this->setReference('currency_EUR', $currencyEUR);
        $this->setReference('currency_GBP', $currencyGBP);
        $this->setReference('currency_CNY', $currencyCNY);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
