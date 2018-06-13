<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class Feature
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ProductBundle\Repository\FeatureRepository")
 */
class Feature implements UuidEntityInterface, FeatureValueAwareInterface
{
    use UuidTrait;
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false, options={"default": 0})
     * @Gedmo\SortablePosition
     */
    private $position;

    /**
     * @var Collection|FeatureValue[]
     *
     * @ORM\OneToMany(
     *     targetEntity="UmberFirm\Bundle\ProductBundle\Entity\FeatureValue",
     *     mappedBy="feature",
     *     cascade={"all"}
     * )
     */
    private $featureValues;

    /**
     * Feature constructor.
     */
    public function __construct()
    {
        $this->featureValues = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return (int) $this->position;
    }

    /**
     * @param null|int $position
     *
     * @return Feature
     */
    public function setPosition(?int $position): Feature
    {
        $this->position = $position;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFeatureValues(): Collection
    {
        return $this->featureValues;
    }

    /**
     * {@inheritdoc}
     */
    public function addFeatureValue(FeatureValue $featureValue): Feature
    {
        if (false === $this->featureValues->contains($featureValue)) {
            $this->featureValues->add($featureValue);
            $featureValue->setFeature($this);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeFeatureValue(FeatureValue $featureValue): Feature
    {
        if (true === $this->featureValues->contains($featureValue)) {
            $this->featureValues->removeElement($featureValue);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        /** @var Translation|FeatureTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getName();
    }

    /**
     * Proxy Translation Method
     *
     * @param string $name
     * @param string $locale
     *
     * @return Feature
     */
    public function setName(string $name, string $locale): Feature
    {
        /** @var Translation|FeatureTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setName($name);

        return $this;
    }
}
