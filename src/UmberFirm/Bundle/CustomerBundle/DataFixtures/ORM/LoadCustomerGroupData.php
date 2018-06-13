<?php

namespace UmberFirm\Bundle\CustomerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerGroup;

/**
 * Class LoadCustomerGroupData
 *
 * @package UmberFirm\Bundle\CustomerBundle\DataFixtures\ORM
 */
class LoadCustomerGroupData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $customerGroup = new CustomerGroup();
        $customerGroup->setName('Visitors', 'en');
        $manager->persist($customerGroup);

        $customerGroup2 = new CustomerGroup();
        $customerGroup2->setName('Registered', 'en');
        $manager->persist($customerGroup2);

        $customerGroup3 = new CustomerGroup();
        $customerGroup3->setName('Clients', 'en');
        $manager->persist($customerGroup3);

        $manager->flush();

        $this->setReference('CustomerGroup:Visitors', $customerGroup);
        $this->setReference('CustomerGroup:Registered', $customerGroup2);
        $this->setReference('CustomerGroup:Clients', $customerGroup3);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 12;
    }
}
