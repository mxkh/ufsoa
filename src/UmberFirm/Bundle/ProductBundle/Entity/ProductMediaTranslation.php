<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Class ProductMediaTranslations
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 *
 * @ORM\Entity
 */
class ProductMediaTranslation
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
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    private $alt;

    /**
     * @param null|string $alt
     *
     * @return ProductMediaTranslation
     */
    public function setAlt(?string $alt): ProductMediaTranslation
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * @return string
     */
    public function getAlt(): string
    {
        return (string) $this->alt;
    }
}
