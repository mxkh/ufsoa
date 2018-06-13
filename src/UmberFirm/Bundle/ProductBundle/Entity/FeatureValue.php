<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class FeatureValue
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ProductBundle\Repository\FeatureValueRepository")
 */
class FeatureValue implements UuidEntityInterface
{
    use UuidTrait;
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @var Feature
     *
     * @ORM\ManyToOne(
     *     targetEntity="UmberFirm\Bundle\ProductBundle\Entity\Feature",
     *     inversedBy="featureValues"
     * )
     * @ORM\JoinColumn(nullable=false)
     */
    private $feature;

    /**
     * @return null|Feature
     */
    public function getFeature(): ?Feature
    {
        return $this->feature;
    }

    /**
     * @param null|Feature $feature
     *
     * @return FeatureValue
     */
    public function setFeature(?Feature $feature): FeatureValue
    {
        $this->feature = $feature;

        if (null !== $feature) {
            $feature->addFeatureValue($this);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        /** @var Translation|FeatureValueTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getValue();
    }

    /**
     * Proxy Translation Method
     *
     * @param string $value
     * @param string $locale
     *
     * @return FeatureValue
     */
    public function setValue(string $value, string $locale): FeatureValue
    {
        /** @var Translation|FeatureValueTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setValue($value);

        return $this;
    }
}
