<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Class StoreTranslation
 *
 * @package UmberFirm\Bundle\ShopBundle\Entity
 *
 * @ORM\Entity
 */
class StoreTranslation
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
     * @ORM\Column(length=155, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(length=155, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(length=155, nullable=true)
     */
    private $schedule;

    /**
     * @return string
     */
    public function getSchedule(): string
    {
        return (string) $this->schedule;
    }

    /**
     * @param null|string $schedule
     *
     * @return StoreTranslation
     */
    public function setSchedule(?string $schedule): StoreTranslation
    {
        $this->schedule = $schedule;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return (string) $this->address;
    }

    /**
     * @param null|string $address
     *
     * @return StoreTranslation
     */
    public function setAddress(?string $address): StoreTranslation
    {
        $this->address = $address;

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
     * @return StoreTranslation
     */
    public function setDescription(?string $description): StoreTranslation
    {
        $this->description = $description;

        return $this;
    }
}
