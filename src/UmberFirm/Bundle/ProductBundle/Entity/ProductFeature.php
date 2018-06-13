<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class ProductFeature
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ProductBundle\Repository\ProductFeatureRepository")
 */
class ProductFeature implements UuidEntityInterface, ProductFeatureValueAwareInterface
{
    use UuidTrait;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(
     *     targetEntity="UmberFirm\Bundle\ProductBundle\Entity\Product",
     *     inversedBy="productFeatures"
     * )
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @var Feature
     *
     * @ORM\ManyToOne(
     *     targetEntity="UmberFirm\Bundle\ProductBundle\Entity\Feature"
     * )
     * @ORM\JoinColumn(nullable=false)
     */
    private $feature;

    /**
     * @var Collection|FeatureValue[]
     *
     * @ORM\ManyToMany(
     *     targetEntity="UmberFirm\Bundle\ProductBundle\Entity\FeatureValue"
     * )
     */
    private $productFeatureValues;

    /**
     * ProductFeature constructor.
     */
    public function __construct()
    {
        $this->productFeatureValues = new ArrayCollection();
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
     * @return ProductFeature
     */
    public function setProduct(?Product $product): ProductFeature
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return null|Feature
     */
    public function getFeature(): ?Feature
    {
        return $this->feature;
    }

    /**
     * @param Feature $feature
     *
     * @return ProductFeature
     */
    public function setFeature(?Feature $feature): ProductFeature
    {
        $this->feature = $feature;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getProductFeatureValues(): Collection
    {
        return $this->productFeatureValues;
    }

    /**
     * {@inheritdoc}
     */
    public function addProductFeatureValue(FeatureValue $featureValue): ProductFeature
    {
        if (false === $this->productFeatureValues->contains($featureValue)) {
            $this->productFeatureValues->add($featureValue);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeProductFeatureValue(FeatureValue $featureValue): ProductFeature
    {
        if (true === $this->productFeatureValues->contains($featureValue)) {
            $this->productFeatureValues->removeElement($featureValue);
        }

        return $this;
    }
}
