<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * SocialProfileEnum
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ShopBundle\Repository\SocialProfileEnumRepository")
 */
class SocialProfileEnum implements UuidEntityInterface
{
    use TimestampableEntity;
    use UuidTrait;
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @var Collection|StoreSocialProfile[]
     * @ORM\OneToMany(targetEntity="StoreSocialProfile", mappedBy="socialProfileEnum")
     */
    private $storeSocialProfiles;

    /**
     * Store constructor.
     */
    public function __construct()
    {
        $this->storeSocialProfiles = new ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     * @param string $locale
     *
     * @return SocialProfileEnum
     */
    public function setName(string $name, string $locale): SocialProfileEnum
    {
        /** @var Translation|SocialProfileEnumTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setName($name);

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
        /** @var Translation|SocialProfileEnumTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getName();
    }

    /**
     * Set alias
     *
     * @param string $alias
     * @param string $locale
     *
     * @return SocialProfileEnum
     */
    public function setAlias(string $alias, string $locale): SocialProfileEnum
    {
        /** @var Translation|SocialProfileEnumTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setAlias($alias);

        return $this;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias(): string
    {
        /** @var Translation|SocialProfileEnumTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getAlias();
    }

    /**
     * @param null|StoreSocialProfile $storeSocialProfile
     *
     * @return SocialProfileEnum
     */
    public function addStoreSocialProfile(?StoreSocialProfile $storeSocialProfile): SocialProfileEnum
    {
        if (false === $this->storeSocialProfiles->contains($storeSocialProfile)) {
            $this->storeSocialProfiles->add($storeSocialProfile);
            $storeSocialProfile->setSocialProfileEnum($this);
        }

        return $this;
    }

    /**
     * @return Collection|StoreSocialProfile[]
     */
    public function getStoreSocialProfiles(): Collection
    {
        return $this->storeSocialProfiles;
    }
}
