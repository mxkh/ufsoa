<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * SettingsAttribute
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ShopBundle\Repository\SettingsAttributeRepository")
 */
class SettingsAttribute implements UuidEntityInterface
{
    use UuidTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=155)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=155)
     */
    private $type;

    /**
     * Set attribute name
     *
     * @param null|string $name
     *
     * @return SettingsAttribute
     */
    public function setName(?string $name): SettingsAttribute
    {
        if (null !== $name) {
            $this->name = $name;
        }

        return $this;
    }

    /**
     * Get attribute name
     *
     * @return string
     */
    public function getName(): string
    {
        return (string) $this->name;
    }

    /**
     * Set type
     *
     * @param null|string $type
     *
     * @return SettingsAttribute
     */
    public function setType(?string $type): SettingsAttribute
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType(): string
    {
        return (string) $this->type;
    }
}
