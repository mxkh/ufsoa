<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\OrderBundle\Entity\OrderItem;

/**
 * Class LoadOrderItemData
 *
 * @package UmberFirm\Bundle\OrderBundle\DataFixtures\ORM
 */
class LoadOrderItemData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $orderItem = new OrderItem();
        $orderItem->setOrder($this->getReference('OrderBundle:Order'));
        $orderItem->setProductVariant($this->getReference('ProductVariant1'));
        $orderItem->setQuantity(3);
        $orderItem->setPrice(333.12);
        $orderItem->setAmount(999.12);
        $manager->persist($orderItem);

        $orderItem2 = new OrderItem();
        $orderItem2->setOrder($this->getReference('OrderBundle:Order2'));
        $orderItem2->setProductVariant($this->getReference('ProductVariant2'));
        $orderItem2->setQuantity(2);
        $orderItem2->setPrice(333.12);
        $orderItem2->setAmount(666.12);
        $manager->persist($orderItem2);

        $orderItem3 = new OrderItem();
        $orderItem3->setOrder($this->getReference('OrderBundle:Order3'));
        $orderItem3->setProductVariant($this->getReference('ProductVariant1'));
        $orderItem3->setQuantity(3);
        $orderItem3->setPrice(333.112);
        $orderItem3->setAmount(999.12);
        $manager->persist($orderItem3);

        $manager->flush();

        $this->setReference('OrderBundle:OrderItem', $orderItem);
        $this->setReference('OrderBundle:OrderItem2', $orderItem2);
        $this->setReference('OrderBundle:OrderItem3', $orderItem3);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 25;
    }
}
