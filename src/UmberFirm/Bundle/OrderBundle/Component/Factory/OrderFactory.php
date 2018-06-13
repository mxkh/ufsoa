<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Component\Factory;

use Doctrine\ORM\EntityManagerInterface;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\Order;
use UmberFirm\Bundle\OrderBundle\Entity\OrderItem;
use UmberFirm\Bundle\OrderBundle\Entity\Promocode;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCartItem;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\PublicBundle\Component\Order\DataTransferObject\FastOrderDTOInterface;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class OrderFactory
 *
 * @package UmberFirm\Bundle\OrderBundle\Component\Factory
 */
class OrderFactory implements OrderFactoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * OrderManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function createFromCart(ShoppingCart $cart): Order
    {
        $order = new Order();
        $order->setQuantity($cart->getQuantity());
        $order->setAmount($cart->getAmount());
        $order->setNumber($this->getUniqueOrderNumber($cart->getShop()));

        return $order;
    }

    /**
     * {@inheritdoc}
     */
    public function createOrderItem(ShoppingCartItem $cartItem): OrderItem
    {
        $orderItem = new OrderItem();
        $orderItem->setProductVariant($cartItem->getProductVariant());
        $orderItem->setQuantity($cartItem->getQuantity());
        $orderItem->setAmount($cartItem->getAmount());
        $orderItem->setPrice($cartItem->getPrice());

        return $orderItem;
    }

    /**
     * {@inheritdoc}
     */
    public function createFromFastOrderDTO(FastOrderDTOInterface $fastOrderDTO): Order
    {
        $customer = $this->entityManager->find(Customer::class, $fastOrderDTO->getCustomer());
        $shop = $this->entityManager->find(Shop::class, $fastOrderDTO->getShop());
        $productVariant = $this->entityManager->find(ProductVariant::class, $fastOrderDTO->getProductVariant());
        $promocode = $this->entityManager->find(Promocode::class, $fastOrderDTO->getPromocode());

        $price = $productVariant->getSalePrice();

        $orderItem = new OrderItem();
        $orderItem->setProductVariant($productVariant);
        $orderItem->setQuantity(1);
        $orderItem->setPrice($price);
        $orderItem->setAmount($price);

        $order = new Order();
        $order->setPromocode($promocode);
        $order->setNumber($this->getUniqueOrderNumber($shop));
        $order->setCustomer($customer);
        $order->setShop($shop);
        $order->setQuantity(1);
        $order->setAmount($price);
        $order->setIsFast(true);
        $order->addOrderItem($orderItem);

        return $order;
    }

    /**
     * {@inheritdoc}
     */
    public function getUniqueOrderNumber(Shop $shop): string
    {
        //TODO: change it to incrementing number
        return sprintf($shop->getName().'-%s', time());
    }
}
