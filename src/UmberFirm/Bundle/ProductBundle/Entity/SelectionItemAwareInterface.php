<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * Interface ItemAwareInterface
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 */
interface SelectionItemAwareInterface
{
    /**
     * @return Collection|SelectionItem[]
     */
    public function getItems(): Collection;

    /**
     * @param SelectionItem $item
     *
     * @return Selection
     */
    public function addItem(SelectionItem $item): Selection;

    /**
     * @param SelectionItem $item
     *
     * @return Selection
     */
    public function removeItem(SelectionItem $item): Selection;
}
