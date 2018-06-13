<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart;

/**
 * Class LoadShoppingCartData
 *
 * @package UmberFirm\Bundle\OrderBundle\DataFixtures\ORM
 */
class LoadShoppingCartData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $shoppingCart = new ShoppingCart();
        $shoppingCart->setShop($this->getReference('HM SHOP'));
        $shoppingCart->setCustomer($this->getReference('CustomerBundle:Customer'));
        $shoppingCart->setAmount(123.12);
        $shoppingCart->setQuantity(1);
        $manager->persist($shoppingCart);

        $shoppingCart2 = new ShoppingCart();
        $shoppingCart2->setShop($this->getReference('POSH SHOP'));
        $shoppingCart2->setCustomer($this->getReference('CustomerBundle:Customer2'));
        $shoppingCart2->setAmount(123.12);
        $shoppingCart2->setQuantity(1);
        $manager->persist($shoppingCart2);

        $shoppingCart3 = new ShoppingCart();
        $shoppingCart3->setShop($this->getReference('MD SHOP'));
        $shoppingCart3->setCustomer($this->getReference('CustomerBundle:Customer3'));
        $shoppingCart3->setAmount(123.12);
        $shoppingCart3->setQuantity(1);
        $manager->persist($shoppingCart3);

        $manager->flush();

        $this->setReference('OrderBundle:ShoppingCart', $shoppingCart);
        $this->setReference('OrderBundle:ShoppingCart2', $shoppingCart2);
        $this->setReference('OrderBundle:ShoppingCart3', $shoppingCart3);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 23;
    }
}
