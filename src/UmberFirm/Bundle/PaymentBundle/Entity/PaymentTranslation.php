<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Class PaymentTranslation
 *
 * @package UmberFirm\Bundle\PaymentBundle\Entity
 *
 * @ORM\Entity
 */
class PaymentTranslation
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
     * @ORM\Column(type="string", length=128)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string) $this->name;
    }

    /**
     * @param null|string $name
     *
     * @return PaymentTranslation
     */
    public function setName(?string $name): PaymentTranslation
    {
        $this->name = $name;

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
     * @return PaymentTranslation
     */
    public function setDescription(?string $description): PaymentTranslation
    {
        $this->description = $description;

        return $this;
    }
}
