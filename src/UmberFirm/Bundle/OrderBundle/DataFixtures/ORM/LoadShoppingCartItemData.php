<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCartItem;

/**
 * Class LoadShoppingCartItemData
 *
 * @package UmberFirm\Bundle\OrderBundle\DataFixtures\ORM
 */
class LoadShoppingCartItemData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $shoppingCartItem = new ShoppingCartItem();
        $shoppingCartItem->setShoppingCart($this->getReference('OrderBundle:ShoppingCart'));
        $shoppingCartItem->setProductVariant($this->getReference('ProductVariant1'));
        $shoppingCartItem->setQuantity(3);
        $shoppingCartItem->setPrice(333.12);
        $shoppingCartItem->setAmount(999.12);
        $manager->persist($shoppingCartItem);

        $shoppingCartItem2 = new ShoppingCartItem();
        $shoppingCartItem2->setShoppingCart($this->getReference('OrderBundle:ShoppingCart2'));
        $shoppingCartItem2->setProductVariant($this->getReference('ProductVariant2'));
        $shoppingCartItem2->setQuantity(2);
        $shoppingCartItem2->setPrice(333.12);
        $shoppingCartItem2->setAmount(666.12);
        $manager->persist($shoppingCartItem2);

        $shoppingCartItem3 = new ShoppingCartItem();
        $shoppingCartItem3->setShoppingCart($this->getReference('OrderBundle:ShoppingCart3'));
        $shoppingCartItem3->setProductVariant($this->getReference('ProductVariant1'));
        $shoppingCartItem3->setQuantity(3);
        $shoppingCartItem3->setPrice(333.12);
        $shoppingCartItem3->setAmount(999.12);
        $manager->persist($shoppingCartItem3);

        $manager->flush();

        $this->setReference('OrderBundle:ShoppingCartItem', $shoppingCartItem);
        $this->setReference('OrderBundle:ShoppingCartItem2', $shoppingCartItem2);
        $this->setReference('OrderBundle:ShoppingCartItem3', $shoppingCartItem3);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 24;
    }
}
