<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class City
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\CommonBundle\Repository\CityRepository")
 */
class City implements UuidEntityInterface, CityStreetAwareInterface, CityBranchAwareInterface
{
    use UuidTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155)
     */
    private $name;

    /**
     * @var string Nova Poshta reference
     *
     * @ORM\Column(type="string", length=255)
     */
    private $ref;

    /**
     * @var Collection|Street[]
     *
     * @ORM\OneToMany(targetEntity="UmberFirm\Bundle\CommonBundle\Entity\Street", mappedBy="city")
     */
    private $streets;

    /**
     * @var Collection|Branch[]
     *
     * @ORM\OneToMany(targetEntity="UmberFirm\Bundle\CommonBundle\Entity\Branch", mappedBy="city")
     */
    private $branches;

    /**
     * City constructor.
     */
    public function __construct()
    {
        $this->streets = new ArrayCollection();
        $this->branches = new ArrayCollection();
    }

    /**
     * @param string $name
     *
     * @return City
     */
    public function setName(string $name): City
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
     * @param string $ref
     *
     * @return City
     */
    public function setRef(?string $ref): City
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

    /**
     * {@inheritdoc}
     */
    public function getStreets(): Collection
    {
        return $this->streets;
    }

    /**
     * {@inheritdoc}
     */
    public function addStreet(Street $street): City
    {
        if (false === $this->streets->contains($street)) {
            $this->streets->add($street);
            $street->setCity($this);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeStreet(Street $street): City
    {
        if (true === $this->streets->contains($street)) {
            $this->streets->removeElement($street);
            $street->setCity(null);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBranches(): Collection
    {
        return $this->branches;
    }

    /**
     * {@inheritdoc}
     */
    public function addBranch(Branch $branch): City
    {
        if (false === $this->branches->contains($branch)) {
            $this->branches->add($branch);
            $branch->setCity($this);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeBranch(Branch $branch): City
    {
        if (true === $this->branches->contains($branch)) {
            $this->branches->removeElement($branch);
            $branch->setCity(null);
        }

        return $this;
    }
}
