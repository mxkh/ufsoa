<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Knp\DoctrineBehaviors\Model\Translatable\Translatable;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * ContactEnum
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ShopBundle\Repository\ContactEnumRepository")
 */
class ContactEnum implements UuidEntityInterface
{
    use TimestampableEntity;
    use UuidTrait;
    use Translatable;

    /**
     * @var Collection|Contact[]
     * @ORM\OneToMany(targetEntity="Contact", mappedBy="contactEnum")
     */
    private $contacts;

    protected $translations;

    /**
     * StoreSocialProfile constructor.
     */
    public function __construct()
    {
        $this->contacts = new ArrayCollection();
    }

    /**
     * Set value
     *
     * @param string $value
     * @param string $locale
     *
     * @return ContactEnum
     */
    public function setValue(string $value, string $locale): ContactEnum
    {
        /** @var Translation|ContactEnumTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setValue($value);

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue(): string
    {
        /** @var Translation|ContactEnumTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getValue();
    }

    /**
     * @param Contact $contact
     *
     * @return ContactEnum
     */
    public function addContact(Contact $contact): ContactEnum
    {
        if (false === $this->contacts->contains($contact)) {
            $this->contacts->add($contact);
            $contact->setContactEnum($this);
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }
}
