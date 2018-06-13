<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\OrderBundle\Entity\PromocodeEnum;

/**
 * Class LoadPromocodeEnumData
 *
 * @package UmberFirm\Bundle\OrderBundle\DataFixtures\ORM
 */
class LoadPromocodeEnumData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $sum = new PromocodeEnum();
        /** @var string $locale */
        $locale = $sum->getCurrentLocale();
        $sum->setCode('sum');
        $sum->setName('Sum', $locale);
        $sum->setCalculate('%s - %s');
        $sum->mergeNewTranslations();
        $manager->persist($sum);

        $percent = new PromocodeEnum();
        /** @var string $locale */
        $locale = $percent->getCurrentLocale();
        $percent->setCode('percent');
        $percent->setName('Percent', $locale);
        $percent->setCalculate('%s * (1 - (%s / 100))');
        $percent->mergeNewTranslations();
        $manager->persist($percent);

        $manager->flush();

        $this->setReference('PromocodeEnum:Sum', $sum);
        $this->setReference('PromocodeEnum:Percent', $percent);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
