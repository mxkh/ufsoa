<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\OrderBundle\Entity\Promocode;

/**
 * Class LoadPromocodeData
 *
 * @package UmberFirm\Bundle\OrderBundle\DataFixtures\ORM
 */
class LoadPromocodeData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $sum500 = new Promocode();
        $sum500->setCode('-500');
        $sum500->setValue(500);
        $sum500->setIsReusable(true);
        $sum500->setPromocodeEnum($this->getReference('PromocodeEnum:Sum'));
        $sum500->setStart(new \DateTime('1.10.2017'));
        $sum500->setFinish(new \DateTime('2.10.2017'));
        $manager->persist($sum500);

        $percent30 = new Promocode();
        $percent30->setCode('SALE_30');
        $percent30->setValue(30);
        $percent30->setIsReusable(false);
        $percent30->setPromocodeEnum($this->getReference('PromocodeEnum:Percent'));
        $manager->persist($percent30);

        $manager->flush();

        $this->setReference('Promocode:Sum:500', $sum500);
        $this->setReference('Promocode:Percent:30', $percent30);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 2;
    }
}
