<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ShopBundle\Entity\ShopGroup;

/**
 * Class LoadShopGroupData
 *
 * @package UmberFirm\Bundle\ShopBundle\DataFixtures\ORM
 */
class LoadShopGroupData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $HMShopGroup = new ShopGroup();
        $HMShopGroup->setName('Helen Marlen Group');

        $MDShopGroup = new ShopGroup();
        $MDShopGroup->setName('MD Group');

        $manager->persist($HMShopGroup);
        $manager->persist($MDShopGroup);
        $manager->flush();

        $this->addReference('HM Group', $HMShopGroup);
        $this->addReference('MD Group', $MDShopGroup);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
