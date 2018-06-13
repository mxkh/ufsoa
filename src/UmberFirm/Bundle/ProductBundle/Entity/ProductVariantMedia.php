<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Component\Doctrine\Entity\Gedmo\Sortable\SortableTrait;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class ProductVariantMedia
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 *
 * @ORM\Table(
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="product_variant_media_idx", columns={"product_media_id", "product_variant_id"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ProductBundle\Repository\ProductVariantMediaRepository")
 */
class ProductVariantMedia
{
    use UuidTrait;
    use SortableTrait;

    /**
     * @var ProductMedia
     *
     * @ORM\ManyToOne(
     *     targetEntity="UmberFirm\Bundle\ProductBundle\Entity\ProductMedia",
     *     inversedBy="productVariantMedia"
     * )
     * @ORM\JoinColumn(nullable=false)
     */
    private $productMedia;

    /**
     * @var ProductVariant
     *
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ProductBundle\Entity\ProductVariant", inversedBy="medias")
     * @ORM\JoinColumn(nullable=false)
     */
    private $productVariant;

    /**
     * @return null|ProductMedia
     */
    public function getProductMedia(): ?ProductMedia
    {
        return $this->productMedia;
    }

    /**
     * @param null|ProductMedia $productMedia
     *
     * @return ProductVariantMedia
     *
     */
    public function setProductMedia(?ProductMedia $productMedia): ProductVariantMedia
    {
        $this->productMedia = $productMedia;

        return $this;
    }

    /**
     * @return null|ProductVariant
     */
    public function getProductVariant(): ?ProductVariant
    {
        return $this->productVariant;
    }

    /**
     * @param null|ProductVariant $productVariant
     *
     * @return ProductVariantMedia
     */
    public function setProductVariant(?ProductVariant $productVariant): ProductVariantMedia
    {
        $this->productVariant = $productVariant;

        return $this;
    }
}
