<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Class FeatureValueTranslation
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 *
 * @ORM\Entity
 */
class FeatureValueTranslation
{
    use ORMBehaviors\Translatable\Translation;

    /**
     * @var string
     *
     * @ORM\Column(length=155)
     */
    protected $locale;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128, nullable=false)
     */
    private $value;

    /**
     * @return string
     */
    public function getValue(): string
    {
        return (string) $this->value;
    }

    /**
     * @param null|string $value
     *
     * @return FeatureValueTranslation
     */
    public function setValue(?string $value): FeatureValueTranslation
    {
        $this->value = $value;

        return $this;
    }
}
