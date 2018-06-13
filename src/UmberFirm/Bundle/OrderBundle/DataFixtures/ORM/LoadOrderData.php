<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\OrderBundle\Entity\Order;

/**
 * Class LoadOrderData
 *
 * @package UmberFirm\Bundle\OrderBundle\DataFixtures\ORM
 */
class LoadOrderData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $order = new Order();
        $order->setShop($this->getReference('HM SHOP'));
        $order->setCustomer($this->getReference('CustomerBundle:Customer'));
        $order->setShopCurrency($this->getReference('HM:USD'));
        $order->setShopPayment($this->getReference('Payment:HM:Cash'));
        $order->setShopDelivery($this->getReference('ShopDelivery:Nova Poshta:Warehouse:HM'));
        $order->setAmount(123.12);
        $order->setNumber('HM'.time());
        $order->setQuantity(1);
        $order->setIsFast(true);
        $manager->persist($order);

        $order2 = new Order();
        $order2->setShop($this->getReference('POSH SHOP'));
        $order2->setCustomer($this->getReference('CustomerBundle:Customer2'));
        $order2->setShopCurrency($this->getReference('POSH:RUB'));
        $order2->setShopPayment($this->getReference('Payment:POSH:Cash'));
        $order2->setShopDelivery($this->getReference('ShopDelivery:Nova Poshta:Addressing:HM'));
        $order2->setNumber('POSH'.time());
        $order2->setAmount(123.12);
        $order2->setQuantity(1);
        $manager->persist($order2);

        $order3 = new Order();
        $order3->setShop($this->getReference('MD SHOP'));
        $order3->setCustomer($this->getReference('CustomerBundle:Customer3'));
        $order3->setNumber('MD'.time());
        $order3->setShopCurrency($this->getReference('MD:CNY'));
        $order3->setShopPayment($this->getReference('Payment:MD:Cash'));
        $order3->setShopDelivery($this->getReference('ShopDelivery:Courier:Consultant:Posh'));
        $order3->setAmount(123.12);
        $order3->setPromocode($this->getReference('Promocode:Sum:500'));
        $order3->setQuantity(1);
        $manager->persist($order3);

        $manager->flush();

        $this->setReference('OrderBundle:Order', $order);
        $this->setReference('OrderBundle:Order2', $order2);
        $this->setReference('OrderBundle:Order3', $order3);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 23;
    }
}
