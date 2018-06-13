<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class Branch
 *
 * @package UmberFirm\Bundle\CommonBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\CommonBundle\Repository\BranchRepository")
 */
class Branch
{
    use UuidTrait;

    /**
     * @var City
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\CommonBundle\Entity\City", inversedBy="branches")
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @var string Nova Poshta reference
     *
     * @ORM\Column(type="string", length=255)
     */
    private $ref;

    /**
     * @param null|City $city
     *
     * @return Branch
     */
    public function setCity(?City $city): Branch
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
     * @return Branch
     */
    public function setName(?string $name): Branch
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
     * @param int|null $number
     *
     * @return Branch
     */
    public function setNumber(?int $number): Branch
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return (int) $this->number;
    }

    /**
     * @param string $ref
     *
     * @return Branch
     */
    public function setRef(?string $ref): Branch
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
