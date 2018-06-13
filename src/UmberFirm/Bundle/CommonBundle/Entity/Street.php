<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class Street
 *
 * @package UmberFirm\Bundle\CommonBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\CommonBundle\Repository\StreetRepository")
 */
class Street implements UuidEntityInterface
{
    use UuidTrait;

    /**
     * @var City
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\CommonBundle\Entity\City", inversedBy="streets")
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155)
     */
    private $type;

    /**
     * @var string Nova Poshta reference
     *
     * @ORM\Column(type="string", length=255)
     */
    private $ref;

    /**
     * @param null|City $city
     *
     * @return Street
     */
    public function setCity(?City $city): Street
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return null|City
     */
    public function getCity(): ?City
    {
        return $this->city;
    }

    /**
     * @param null|string $name
     *
     * @return Street
     */
    public function setName(?string $name): Street
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string) $this->name;
    }

    /**
     * @param null|string $type
     *
     * @return Street
     */
    public function setType(?string $type): Street
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return (string) $this->type;
    }

    /**
     * @param string $ref
     *
     * @return Street
     */
    public function setRef(?string $ref): Street
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     * @return string
     */
    public function getRef(): string
    {
        return (string) $this->ref;
    }
}
