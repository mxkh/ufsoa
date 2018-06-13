<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CategoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Class CategorySeoTranslation
 *
 * @package UmberFirm\Bundle\CategoryBundle\Entity
 *
 * @ORM\Entity
 */
class CategorySeoTranslation
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
     * @return CategorySeoTranslation
     */
    public function setTitle(?string $title): CategorySeoTranslation
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
     * @return CategorySeoTranslation
     */
    public function setKeywords(?string $keywords): CategorySeoTranslation
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
     * @return CategorySeoTranslation
     */
    public function setDescription(?string $description): CategorySeoTranslation
    {
        $this->description = $description;

        return $this;
    }
}
