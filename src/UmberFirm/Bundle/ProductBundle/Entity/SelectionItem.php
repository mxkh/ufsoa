<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * SelectionItem
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="selection_item_idx", columns={"selection_id", "product_id"})})
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ProductBundle\Repository\SelectionItemRepository")
 */
class SelectionItem implements UuidEntityInterface
{
    use UuidTrait;

    /**
     * @var Selection
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ProductBundle\Entity\Selection", inversedBy="items")
     */
    private $selection;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ProductBundle\Entity\Product")
     */
    private $product;

    /**
     * @return null|Selection
     */
    public function getSelection(): ?Selection
    {
        return $this->selection;
    }

    /**
     * @param null|Selection $selection
     *
     * @return SelectionItem
     */
    public function setSelection(?Selection $selection): SelectionItem
    {
        $this->selection = $selection;

        return $this;
    }

    /**
     * @return null|Product
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param null|Product $product
     *
     * @return SelectionItem
     */
    public function setProduct(?Product $product): SelectionItem
    {
        $this->product = $product;

        return $this;
    }
}
