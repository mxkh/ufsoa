<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;

/**
 * Class Payment
 *
 * @package UmberFirm\Bundle\PaymentBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\PaymentBundle\Repository\PaymentRepository")
 */
class Payment implements UuidEntityInterface
{
    use UuidTrait;
    use ORMBehaviors\Translatable\Translatable;

    const OFFLINE = 0;
    const ONLINE = 1;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155, unique=true)
     */
    private $code;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false, options={"default": 0})
     */
    private $type = self::OFFLINE;

    /**
     * @return string
     */
    public function getCode(): string
    {
        return (string) $this->code;
    }

    /**
     * @param null|string $code
     *
     * @return Payment
     */
    public function setCode(?string $code): Payment
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @param null|int $type
     *
     * @return Payment
     */
    public function setType(?int $type): Payment
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return (int) $this->type;
    }

    /**
     * Proxy method for translations
     *
     * @return string
     */
    public function getName(): string
    {
        /** @var Translation|PaymentTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getName();
    }

    /**
     * Proxy Translation Method
     *
     * @param string $name
     * @param string $locale
     *
     * @return Payment
     */
    public function setName(string $name, string $locale): Payment
    {
        /** @var Translation|PaymentTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setName($name);

        return $this;
    }

    /**
     * Proxy method for translations
     *
     * @return string
     */
    public function getDescription(): string
    {
        /** @var Translation|PaymentTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getDescription();
    }

    /**
     * Proxy Translation Method
     *
     * @param string $description
     * @param string $locale
     *
     * @return Payment
     */
    public function setDescription(string $description, string $locale): Payment
    {
        /** @var Translation|PaymentTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setDescription($description);

        return $this;
    }
}
