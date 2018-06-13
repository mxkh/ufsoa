<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ManufacturerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Manufacturer
 *
 * @package UmberFirm\Bundle\ManufacturerBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ManufacturerBundle\Repository\ManufacturerRepository")
 */
class Manufacturer implements UuidEntityInterface
{
    use TimestampableEntity;
    use UuidTrait;
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @var ArrayCollection|Shop[]
     *
     * @ORM\ManyToMany(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\Shop", inversedBy="manufacturers")
     */
    private $shops;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=128, nullable=true)
     */
    private $website;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"}, updatable=false)
     *
     * @ORM\Column(length=155, unique=true, nullable=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    private $reference;

    /**
     * Manufacturer constructor.
     */
    public function __construct()
    {
        $this->shops = new ArrayCollection();
    }

    /**
     * @return null|string
     */
    public function getWebsite(): ?string
    {
        return $this->website;
    }

    /**
     * @param string|null $website
     *
     * @return Manufacturer
     */
    public function setWebsite(?string $website): Manufacturer
    {
        $this->website = $website;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     *
     * @return Manufacturer
     */
    public function setName(?string $name): Manufacturer
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param null|string $slug
     *
     * @return Manufacturer
     */
    public function setSlug(?string $slug): Manufacturer
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return (string) $this->slug;
    }

    /**
     * @return Collection
     */
    public function getShops(): Collection
    {
        return $this->shops;
    }

    /**
     * @param Shop $shop
     *
     * @return Manufacturer
     */
    public function addShop(Shop $shop): Manufacturer
    {
        if (false === $this->shops->contains($shop)) {
            $this->shops->add($shop);
        }

        return $this;
    }

    /**
     * @param null|string $reference
     *
     * @return Manufacturer
     */
    public function setReference(?string $reference): Manufacturer
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return string
     */
    public function getReference(): string
    {
        return (string) $this->reference;
    }

    /**
     * @return null|string
     */
    public function getAddress(): ?string
    {
        /** @var Translation|ManufacturerTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getAddress();
    }

    /**
     * @param string $address
     * @param string $locale
     *
     * @return Manufacturer
     */
    public function setAddress(string $address, string $locale): Manufacturer
    {
        /** @var Translation|ManufacturerTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setAddress($address);

        return $this;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        /** @var Translation|ManufacturerTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getDescription();
    }

    /**
     * @param string $description
     * @param string $locale
     *
     * @return Manufacturer
     */
    public function setDescription(string $description, string $locale): Manufacturer
    {
        /** @var Translation|ManufacturerTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setDescription($description);

        return $this;
    }
}
