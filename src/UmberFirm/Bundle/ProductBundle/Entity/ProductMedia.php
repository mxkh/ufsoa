<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;
use UmberFirm\Bundle\MediaBundle\Entity\Media;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Component\Doctrine\Entity\Gedmo\Sortable\SortableTrait;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class ProductMedia
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 *
 * @ORM\Table(
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="product_media_idx",
 *              columns={"product_id", "shop_id", "media_id"}
 *          )
 *     }
 * )
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ProductBundle\Repository\ProductMediaRepository")
 */
class ProductMedia implements UuidEntityInterface
{
    use UuidTrait;
    use ORMBehaviors\Translatable\Translatable;
    use SortableTrait;

    /**
     * @var Product
     *
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ProductBundle\Entity\Product", inversedBy="medias")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @var Shop
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\Shop")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shop;

    /**
     * @var Media
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\MediaBundle\Entity\Media", inversedBy="productMedias")
     * @ORM\JoinColumn(nullable=false)
     */
    private $media;

    /**
     * @var ProductVariantMedia[]|Collection
     *
     * @ORM\OneToMany(
     *     targetEntity="UmberFirm\Bundle\ProductBundle\Entity\ProductVariantMedia",
     *     mappedBy="productMedia",
     *     cascade={"remove"}
     * )
     */
    private $productVariantMedia;

    /**
     * ProductMedia constructor.
     */
    public function __construct()
    {
        $this->productVariantMedia = new ArrayCollection();
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
     * @return ProductMedia
     */
    public function setProduct(?Product $product): ProductMedia
    {
        $this->product = $product;

        return $this;
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
     * @return ProductMedia
     */
    public function setShop(?Shop $shop): ProductMedia
    {
        $this->shop = $shop;

        return $this;
    }

    /**
     * @return null|Media
     */
    public function getMedia(): ?Media
    {
        return $this->media;
    }

    /**
     * @param null|Media $media
     *
     * @return ProductMedia
     */
    public function setMedia(?Media $media): ProductMedia
    {
        $this->media = $media;

        return $this;
    }

    /**
     * @return string
     */
    public function getAlt(): string
    {
        /** @var Translation|ProductMediaTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getAlt();
    }

    /**
     * @param string $alt
     * @param string $locale
     *
     * @return ProductMedia
     */
    public function setAlt(string $alt, string $locale): ProductMedia
    {
        /** @var Translation|ProductMediaTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setAlt($alt);

        return $this;
    }
}
