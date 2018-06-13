<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Component\Manager;

use Doctrine\ORM\EntityManagerInterface;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCartItem;

/**
 * Class ShoppingCartItemManager
 *
 * @package UmberFirm\Bundle\OrderBundle\Component\Manager
 */
class ShoppingCartItemManager implements ShoppingCartItemManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ShoppingCartItemManager constructor.
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
    public function manage(ShoppingCartItem $shoppingCartItem): ShoppingCartItem
    {
        $existedShoppingCartItem = $this->getExistedShoppingCartItem($shoppingCartItem);

        $this->entityManager->getConnection()->beginTransaction();
        if (null === $existedShoppingCartItem) {
            $this->save($shoppingCartItem);

            return $shoppingCartItem;
        }

        return $this->update($existedShoppingCartItem, $shoppingCartItem);
    }

    /**
     * {@inheritdoc}
     */
    public function save(ShoppingCartItem $shoppingCartItem): bool
    {
        try {
            $this->entityManager->persist($shoppingCartItem);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $exception) {
            // TODO: Add logger
            $this->entityManager->rollback();

            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function update(ShoppingCartItem $existedShoppingCartItem, ShoppingCartItem $shoppingCartItem): ShoppingCartItem
    {
        $existedShoppingCartItem->setQuantity($shoppingCartItem->getQuantity());
        $existedShoppingCartItem->setAmount($shoppingCartItem->getAmount());
        $existedShoppingCartItem->setPrice($shoppingCartItem->getPrice());
        $this->save($existedShoppingCartItem);

        return $existedShoppingCartItem;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * @param ShoppingCartItem $shoppingCartItem
     *
     * @return null|ShoppingCartItem
     */
    protected function getExistedShoppingCartItem(ShoppingCartItem $shoppingCartItem): ?ShoppingCartItem
    {
        $shoppingCartItemRepository = $this->entityManager->getRepository(ShoppingCartItem::class);

        return $shoppingCartItemRepository->findOneBy(
            [
                'shoppingCart' => $shoppingCartItem->getShoppingCart()->getId()->toString(),
                'productVariant' => $shoppingCartItem->getProductVariant()->getId()->toString(),
            ]
        );
    }
}
