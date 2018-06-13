<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Class ProductSeoTranslation
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 *
 * @ORM\Entity
 */
class ProductSeoTranslation
{
    use ORMBehaviors\Translatable\Translation;

    /**
     * @var string
     *
     * @ORM\Column(length=155)
     */
    protected $locale;

    /**
     *
     * @var string
     *
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    private $keywords;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return (string) $this->title;
    }

    /**
     * @param null|string $title
     *
     * @return ProductSeoTranslation
     */
    public function setTitle(?string $title): ProductSeoTranslation
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getKeywords(): string
    {
        return (string) $this->keywords;
    }

    /**
     * @param null|string $keywords
     *
     * @return ProductSeoTranslation
     */
    public function setKeywords(?string $keywords): ProductSeoTranslation
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return (string) $this->description;
    }

    /**
     * @param null|string $description
     *
     * @return ProductSeoTranslation
     */
    public function setDescription(?string $description): ProductSeoTranslation
    {
        $this->description = $description;

        return $this;
    }
}
