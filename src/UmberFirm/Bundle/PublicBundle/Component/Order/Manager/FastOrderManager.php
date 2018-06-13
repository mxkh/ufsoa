<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Order\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use UmberFirm\Bundle\OrderBundle\Component\Factory\OrderFactoryInterface;
use UmberFirm\Bundle\PublicBundle\Component\Order\DataTransferObject\FastOrderDTOInterface;

/**
 * Class FastOrderManager
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Order\Manager
 */
class FastOrderManager implements FastOrderManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var OrderFactoryInterface
     */
    private $orderFactory;

    /**
     * FastOrderManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param OrderFactoryInterface $orderFactory
     */
    public function __construct(EntityManagerInterface $entityManager, OrderFactoryInterface $orderFactory)
    {
        $this->entityManager = $entityManager;
        $this->orderFactory = $orderFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function manage(FastOrderDTOInterface $fastOrderDTO): bool
    {
        $order = $this->orderFactory->createFromFastOrderDTO($fastOrderDTO);

        $this->entityManager->persist($order);

        try {
            $this->entityManager->flush();
        } catch (Exception $exception) {
            return false;
        }

        return true;
    }
}
