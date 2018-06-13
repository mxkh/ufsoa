<?php

namespace UmberFirm\Bundle\CustomerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;

/**
 * Class LoadCustomerData
 *
 * @package UmberFirm\Bundle\CustomerBundle\DataFixtures\ORM
 */
class LoadCustomerData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $customer = new Customer();
        $customer->setEmail('test@gmail.com');
        $customer->setPhone('380951234562');
        $customer->setCustomerGroup($this->getReference('CustomerGroup:Visitors'));
        $customer->setShop($this->getReference('HM SHOP'));
        $customer->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $manager->persist($customer);

        $customer2 = new Customer();
        $customer2->setEmail('test2@gmail.com');
        $customer2->setPhone('380951234563');
        $customer2->setCustomerGroup($this->getReference('CustomerGroup:Registered'));
        $customer2->setShop($this->getReference('POSH SHOP'));
        $customer2->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $manager->persist($customer2);

        $customer3 = new Customer();
        $customer3->setEmail('test2@gmail.com');
        $customer3->setPhone('380951234567');
        $customer3->setCustomerGroup($this->getReference('CustomerGroup:Clients'));
        $customer3->setShop($this->getReference('MD SHOP'));
        $customer3->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $manager->persist($customer3);

        $manager->flush();

        $this->setReference('CustomerBundle:Customer', $customer);
        $this->setReference('CustomerBundle:Customer2', $customer2);
        $this->setReference('CustomerBundle:Customer3', $customer3);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 13;
    }
}
