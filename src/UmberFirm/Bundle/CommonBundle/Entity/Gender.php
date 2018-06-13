<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Class Gender
 *
 * @package UmberFirm\Bundle\CommonBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\CommonBundle\Repository\GenderRepository")
 */
class Gender implements UuidEntityInterface
{
    use UuidTrait;
    use ORMBehaviors\Translatable\Translatable;

    /**
     * Proxy translation method
     *
     * @return null|string
     */
    public function getName(): ?string
    {
        /** @var Translation|GenderTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getName();
    }

    /**
     * Proxy Translation Method
     *
     * @param string $name
     * @param string $locale
     *
     * @return Gender
     */
    public function setName(string $name, string $locale): Gender
    {
        /** @var Translation|GenderTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setName($name);

        return $this;
    }
}
