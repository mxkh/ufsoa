<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Component\Manager;

use Doctrine\ORM\EntityManagerInterface;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCartItem;

/**
 * Interface ShoppingCartItemManagerInterface
 *
 * @package UmberFirm\Bundle\OrderBundle\Component\Manager
 */
interface ShoppingCartItemManagerInterface
{
    /**
     * @param ShoppingCartItem $dto
     *
     * @throws \Exception
     *
     * @return ShoppingCartItem
     */
    public function manage(ShoppingCartItem $dto): ShoppingCartItem;

    /**
     * @param ShoppingCartItem $shoppingCartItem
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function save(ShoppingCartItem $shoppingCartItem): bool;

    /**
     * @param ShoppingCartItem $shoppingCartItemOld
     * @param ShoppingCartItem $shoppingCartItemNew
     *
     * @throws \Exception
     *
     * @return ShoppingCartItem
     */
    public function update(ShoppingCartItem $shoppingCartItemOld, ShoppingCartItem $shoppingCartItemNew): ShoppingCartItem;

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface;
}
