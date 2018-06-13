<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Selection
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ProductBundle\Repository\SelectionRepository")
 */
class Selection implements UuidEntityInterface, SelectionItemAwareInterface
{
    use UuidTrait;
    use TimestampableEntity;
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @var Shop
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\Shop")
     */
    private $shop;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=false, options={"default":false})
     */
    private $isActive = false;

    /**
     * @var Collection|SelectionItem[]
     *
     * @ORM\OneToMany(
     *     targetEntity="UmberFirm\Bundle\ProductBundle\Entity\SelectionItem",
     *     mappedBy="selection",
     *     cascade={"remove"}
     * )
     */
    private $items;

    /**
     * Selection constructor.
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * @return null|Shop
     */
    public function getShop(): ?Shop
    {
        return $this->shop;
    }

    /**
     * @param null|Shop $shop
     *
     * @return Selection
     */
    public function setShop(?Shop $shop): Selection
    {
        $this->shop = $shop;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsActive(): bool
    {
        return (bool) $this->isActive;
    }

    /**
     * @param bool|null $isActive
     *
     * @return Selection
     */
    public function setIsActive(?bool $isActive): Selection
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Proxy Translation Method
     *
     * @return string
     */
    public function getDescription(): string
    {
        /** @var Translation|SelectionTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getDescription();
    }

    /**
     * Proxy Translation Method
     *
     * @param string $description
     * @param string $locale
     *
     * @return Selection
     */
    public function setDescription(string $description, string $locale): Selection
    {
        /** @var Translation|SelectionTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setDescription($description);

        return $this;
    }

    /**
     * Proxy Translation Method
     *
     * @return string
     */
    public function getName(): string
    {
        /** @var Translation|SelectionTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getName();
    }

    /**
     * Proxy Translation Method
     *
     * @param string $name
     * @param string $locale
     *
     * @return Selection
     */
    public function setName(string $name, string $locale): Selection
    {
        /** @var Translation|SelectionTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setName($name);

        return $this;
    }

    /**
     * Proxy Translation Method
     *
     * @return string
     */
    public function getSlug(): string
    {
        /** @var Translation|SelectionTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getSlug();
    }

    /**
     * {@inheritdoc}
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /**
     * {@inheritdoc}
     */
    public function addItem(SelectionItem $item): Selection
    {
        if (false === $this->items->contains($item)) {
            $item->setSelection($this);
            $this->items->add($item);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeItem(SelectionItem $item): Selection
    {
        if (true === $this->items->contains($item)) {
            $this->items->removeElement($item);
            $item->setSelection(null);
        }

        return $this;
    }
}
