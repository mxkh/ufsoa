<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Order\Manager;

use Doctrine\ORM\EntityManagerInterface;
use UmberFirm\Bundle\OrderBundle\Entity\Order;
use UmberFirm\Bundle\PublicBundle\Component\Order\Builder\OrderBuilderInterface;
use UmberFirm\Bundle\PublicBundle\Component\Order\Producer\OrderProducerInterface;
use UmberFirm\Bundle\PublicBundle\DataObject\PublicOrder;

/**
 * Class OrderManager
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Order\Manager
 */
class OrderManager implements OrderManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var OrderBuilderInterface
     */
    private $orderBuilder;

    /**
     * @var OrderProducerInterface
     */
    private $orderProducer;

    /**
     * OrderManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param OrderBuilderInterface $orderBuilder
     * @param OrderProducerInterface $orderProducer
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        OrderBuilderInterface $orderBuilder,
        OrderProducerInterface $orderProducer
    ) {
        $this->entityManager = $entityManager;
        $this->orderBuilder = $orderBuilder;
        $this->orderProducer = $orderProducer;
    }

    /**
     * {@inheritdoc}
     */
    public function manage(PublicOrder $publicOrder): PublicOrder
    {
        $order = $this->buildOrder($publicOrder);

        $shoppingCart = $publicOrder->getShoppingCart()->setArchived(true);
        $this->entityManager->persist($shoppingCart);
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        $this->prepareOrderPublic($publicOrder, $order);
        $this->orderProducer->sendOrder($publicOrder);

        return $publicOrder;
    }

    /**
     * {@inheritdoc}
     */
    public function buildOrder(PublicOrder $publicOrder): Order
    {
        return $this->orderBuilder
            ->createOrder($publicOrder->getShoppingCart())
            ->addOrderItems($publicOrder->getShoppingCart()->getShoppingCartItems())
            ->addShop($publicOrder->getShop())
            ->addCustomer($publicOrder->getCustomer())
            ->addCurrency($publicOrder->getShopCurrency())
            ->addPromocode($publicOrder->getPromocode())
            ->addDelivery($publicOrder->getShopDelivery())
            ->addShippingAddress($publicOrder->createCustomerAddress())
            ->addPayment($publicOrder->getShopPayment())
            ->addNote($publicOrder->getNote())
            ->getOrder();
    }

    /**
     * {@inheritdoc}
     */
    public function prepareOrderPublic(PublicOrder $publicOrder, Order $order): PublicOrder
    {
        $publicOrder->setNumber($order->getNumber());
        $publicOrder->setPaymentUrl($order->getPaymentUrl());

        return $publicOrder;
    }
}
